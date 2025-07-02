<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCompanyDetailRequest;
use App\Http\Requests\ChangeAddressRequest;
use App\Http\Requests\ChangeCompanyAddressRequest;
use App\Http\Requests\ChangeCompanyFieldRequest;
use App\Http\Requests\ChangeCompanyNameRequest;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangeNameRequest;
use App\Http\Requests\ChangePhoneRequest;
use App\Http\Requests\ResendChangeEmailOtp;
use App\Http\Requests\ResendVerificationSMSRequest;
use App\Http\Requests\UploadProfilePictureDocRequest;
use App\Http\Requests\VerifyNewEmailOtpRequest;
use App\Http\Requests\VerifyPhoneNumberOTPRequest;
use App\Http\Requests\VerifyPhoneNumberRequest;
use App\Http\Resources\API\ActivityResource;
use App\Http\Resources\API\UserResource;
use App\Http\Resources\SuccessResource;
use App\Models\ActivityLog;
use App\Models\Company;
use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    private $basic;
    private $client;

    public function __construct()
    {
        $this->basic = new \Vonage\Client\Credentials\Basic(env("NEXMO_KEY"), env("NEXMO_SECRET"));
        $this->client = new \Vonage\Client($this->basic);
    }

    public function profile()
    {
        return UserResource::make(\auth()->user());
    }

    public function uploadProfilePicture(UploadProfilePictureDocRequest $request)
    {
        try {
            $ext = explode('/', mime_content_type($request->base64_image))[1];
            $filename = 'profile_img_' . time() . '.' . $ext;
            $path = 'images/' . $request->user()->id;
            $image_parts = explode(";base64,", $request->base64_image);
            $image_base64 = base64_decode($image_parts[1]);
            Storage::disk('public')->put($path . '/' . $filename, $image_base64);
            $profile_photo_path = $path . '/' . $filename;
            $user = User::find($request->user()->id);
            $user->profile_photo_path = $profile_photo_path;
            $saved = $user->save();
            if ( !$saved ) {
                throw new Exception("Error Processing Request");
            }
            return SuccessResource::make(['message' => 'Profile picture updated successfully!']);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     **/
    public function changeEmail(ChangeEmailRequest $request)
    {
        try {
            $user = $request->user();
            $token = rand(1000, 9999);
            /* for web */
            $rand_str = Str::random(30);
            $verification_url = route('verifyEmail', ['validate_str' => $rand_str]);
            /* for web */
            UserToken::updateOrInsert([
                'user_id' => $user->id,
                'type'    => 'new_email',
            ], [
                'token'      => $token,
                'created_at' => Carbon::now(),
            ]);
            $details['title'] = "Please verify your email";
            $details['body'] = 'Please click the below link(for web) or use otp(for app) to verify this as your new email for ' . config('app.name') . ' login.';
            $details['url'] = $verification_url;
            $details['token'] = $token;
            $updateUser = User::where('email', $user->email)->update([
                'new_email'                 => $request->new_email,
                'email_verified'            => false,
                'email_verification_string' => $rand_str,
            ]);
            if ( $updateUser ) {
                Mail::to($request->new_email)->send(new \App\Mail\EmailVerifyMail($details));
                activity()
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('verification email on <b>:subject.new_email</b> has been Sent');
                return SuccessResource::make(['message' => 'Verification email with OTP sent successfully!']);
            } else {
                throw new Exception("Error Processing Request");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function resendChangeEmailOtp(ResendChangeEmailOtp $request)
    {
        try {
            $user = $request->user();
            $token = rand(1000, 9999);
            /* for web */
            $rand_str = Str::random(30);
            $verification_url = route('verifyEmail', ['validate_str' => $rand_str]);
            /* for web */
            UserToken::updateOrInsert([
                'user_id' => $user->id,
                'type'    => 'new_email',
            ], [
                'token'      => $token,
                'created_at' => Carbon::now(),
            ]);
            $details['title'] = "Please verify your email";
            $details['body'] = 'Please click the below link(for web) or use otp(for app) to verify this as your new email for ' . config('app.name') . ' login.';
            $details['url'] = $verification_url;
            $details['token'] = $token;
            $updateUser = User::where('email', $user->email)->update([
                'email_verified'            => false,
                'email_verification_string' => $rand_str,
            ]);
            if ( $updateUser ) {
                Mail::to($user->new_email)->send(new \App\Mail\EmailVerifyMail($details));
                activity()
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('verification email on <b>:subject.new_email</b> has been Sent');
                return SuccessResource::make(['message' => 'Verification email with OTP sent successfully!']);
            } else {
                throw new Exception("Error Processing Request");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function verifyNewEmailOtp(VerifyNewEmailOtpRequest $request)
    {
        try {
            $user = User::where('id', $request->user()->id)
                ->where('new_email', '!=', null)
                ->first();
            if ( $user ) {
                $user->email = $user->new_email;
                $user->new_email = null;
                $user->email_verification_string = null;
                $user->email_verified = true;
                if ( $user->save() ) {
                    $deleteToken = UserToken::where('user_id', $request->user()->id)
                        ->where('type', 'new_email')
                        ->where('token', $request->token)
                        ->delete();
                    return SuccessResource::make(['message' => 'Email verified successfully!']);
                    // activity()
                    // ->performedOn($user)
                    // ->causedBy(auth()->user())
                    // ->log('email:<b>:subject.email</b> has been verified successfully');
                } else {
                    throw new Exception("Error Processing Request");
                }
            } else {
                throw new Exception("Error Processing Request1");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function changeName(ChangeNameRequest $request)
    {
        try {
            $user = User::find($request->user()->id);
            if ( $user ) {
                $user->name = $request->name;
                if ( $user->save() ) {
                    return SuccessResource::make(['message' => 'Name changed successfully!']);
                    activity()
                        ->performedOn($user)
                        ->causedBy($request->user())
                        ->log('name:<b>:subject.name</b> has been changed successfully');
                } else {
                    throw new Exception("Error Processing Request");
                }
            } else {
                throw new Exception("Error Processing Request1");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function changeAddress(ChangeAddressRequest $request)
    {
        try {

            $user = User::find($request->user()->id);
            if ( $user ) {
                $user->street = $request->street;
                $user->country_id = $request->country_id;
                $user->city = $request->city;
                if ( $user->save() ) {
                    return SuccessResource::make(['message' => 'Address updated successfully!']);
                    activity()
                        ->performedOn($user)
                        ->causedBy($request->user())
                        ->log('Address with street: <b> :subject.street</b> city: <b>:subject.city</b> country: <b>:subject.country.name</b> has been Updated');
                } else {
                    throw new Exception("Error Processing Request");
                }
            } else {
                throw new Exception("Error Processing Request1");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function changeCompanyName(ChangeCompanyNameRequest $request)
    {
        try {
            $user = User::find($request->user()->id);
            if ( $user && $user->company ) {
                $company = $user->company;
                $company->name = $request->name;
                $company->save();
                if ( $company->save() ) {
                    return SuccessResource::make(['message' => 'Company name changed successfully!']);
                    activity()
                        ->performedOn($company)
                        ->causedBy($request->user())
                        ->log('Company name <b> :subject.street</b> changed successfully');
                } else {
                    throw new Exception("Error Processing Request");
                }
            } else {
                throw new Exception("Error Processing Request1");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function changeCompanyField(ChangeCompanyFieldRequest $request)
    {
        try {
            $user = User::find($request->user()->id);
            if ( $user && $user->company ) {
                $company = $user->company;
                $company->field = $request->field;
                $company->save();
                if ( $company->save() ) {
                    return SuccessResource::make(['message' => 'Company field of business changed successfully!']);
                    activity()
                        ->performedOn($company)
                        ->causedBy($request->user())
                        ->log('Company field of business <b> :subject.field</b> changed successfully');
                } else {
                    throw new Exception("Error Processing Request");
                }
            } else {
                throw new Exception("Error Processing Request1");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function changeCompanyAddress(ChangeCompanyAddressRequest $request)
    {
        try {
            $user = User::find($request->user()->id);
            if ( $user ) {
                $company = $user->company;
                $company->street = $request->street;
                $company->country_id = $request->country_id;
                $company->city = $request->city;
                if ( $company->save() ) {
                    return SuccessResource::make(['message' => 'Company address updated successfully!']);
                    activity()
                        ->performedOn($company)
                        ->causedBy($user)
                        ->log('Company address with street: <b> :subject.street</b> city: <b>:subject.city</b> country: <b>:subject.country.name</b> has been Updated.');
                } else {
                    throw new Exception("Error Processing Request");
                }
            } else {
                throw new Exception("Error Processing Request1");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function addCompanyDetails(AddCompanyDetailRequest $request)
    {
        try {
            $user = User::find($request->user()->id);
            if ( $user ) {
                $inputs = $request->only(['name', 'field', 'registration_id']);
                $inputs['user_id'] = $user->id;
                if ( $request->incorporation_doc ) {
                    $full_name = $request->incorporation_doc->getClientOriginalName();
                    $ext = pathinfo($full_name, PATHINFO_EXTENSION);
                    $filename = 'incorporation_doc_url' . '.' . $ext;
                    $path = 'images/' . $user->id;
                    $request->incorporation_doc->storeAs($path, $filename, 'public');
                    $inputs['incorporation_doc_url'] = $path . '/' . $filename;
                }
                $company = Company::create($inputs);
                if ( $company ) {
                    return SuccessResource::make(['message' => 'Company details saved successfully!']);
                    activity()
                        ->performedOn($company)
                        ->causedBy($user)
                        ->log('Company details with name: <b> :subject.name</b> registration_id: <b>:subject.registration_id</b> field of business: <b>:subject.field</b> has been Updated.');
                } else {
                    throw new Exception("Error Processing Request");
                }
            } else {
                throw new Exception("Error Processing Request1");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function changePhone(ChangePhoneRequest $request)
    {
        try {
            $user = User::find($request->user()->id);
            /** for mobile */
            $token = rand(1000, 9999);
            /** */
            $rand_str = Str::random(30);
            $verification_url = route('verifyPhoneNumber', ['validate_str' => $rand_str]);
            $message = "Please Verify your phone number by clicking on the link(for web) or by entring OTP(for mobile) mentioned below.\n link:" . $verification_url . "\n OTP:" . $token;
            /**SMS Sending Process */
            $res = $this->sendSMS($request->phone_prefix . $request->phone, $message);
            /**SMS Sending Process End*/
            if ( !$res['error'] ) {
                UserToken::updateOrInsert([
                    'user_id' => $user->id,
                    'type'    => 'phone',
                ], [
                    'token'      => $token,
                    'created_at' => Carbon::now(),
                ]);
                $user->phone_prefix = $request->phone_prefix;
                $user->phone = $request->phone;
                $user->phone_verified = false;
                $user->phone_verification_string = $rand_str;
                if ( $user->save() ) {
                    activity()
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['phone_prefix' => $request->phone_prefix, 'phone' => $request->phone, 'phone_verification_string' => $rand_str])
                        ->log('Verification link sent to <b>:subject.phone_prefix :subject.phone</b>');
                    return SuccessResource::make(['message' => 'Phone verification link with OTP sent successfully!', 'otp' => $token]);
                } else {
                    throw new Exception("Error Processing Request");
                }
            } else {
                session()->flash('error', $res['msg']);
                $this->emitSelf('refresh');
                $this->emit('reRenderParent');
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * send sms to phone
     */
    public function sendSMS($mobile, $message = "Test SMS")
    {
        try {
            $sms = new \Vonage\SMS\Message\SMS($mobile, config('app.name'), $message);
            // $response = $this->client->sms()->send([
            //     'to' => '919829190963',
            //     'from' => config('app.name'),
            //     'text' => 'test message 12'
            // ]);
            $response = $this->client->sms()->send($sms);
            return ['error' => false, "msg" => 'sms sent', 'data' => $response];
        } catch (Exception $e) {
            return ['error' => true, "msg" => $e->getMessage(), 'data' => null];
        }
    }

    /**
     * verify phone number via otp
     *
     * @param int otp
     * @author Moh Ashraf
     *
     */
    public function verifyPhoneNumberOTP(VerifyPhoneNumberOTPRequest $request)
    {
        try {
            $user = User::where('id', $request->user()->id)->first();
            $user->phone_verification_string = null;
            $user->phone_verified = true;
            if ( $user->save() ) {
                $deleteToken = UserToken::where('user_id', $request->user()->id)
                    ->where('type', 'phone')
                    ->where('token', $request->otp)
                    ->delete();
                activity()
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('Phone:<b>:subject.phone_prefix-:subject.phone</b> has been verified successfully');
                return SuccessResource::make(['message' => 'Phone number has been verified successfully!']);
            } else {
                throw new Exception("Error Processing Request");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @author Moh Ashraf
     */
    public function resendVerificationSMS(ResendVerificationSMSRequest $request)
    {
        try {
            $user = User::find($request->user()->id);
            /** for mobile */
            $token = rand(1000, 9999);
            /** */
            $rand_str = Str::random(30);
            $verification_url = route('verifyPhoneNumber', ['validate_str' => $rand_str]);
            $message = "Please Verify your phone number by clicking on the link(for web) or by entring OTP(for mobile) mentioned below.\n link:" . $verification_url . "\n OTP:" . $token;
            /**SMS Sending Process */
            $res = $this->sendSMS($user->phone_prefix . $user->phone, $message);
            /**SMS Sending Process End*/
            if ( !$res['error'] ) {
                UserToken::updateOrInsert([
                    'user_id' => $user->id,
                    'type'    => 'phone',
                ], [
                    'token'      => $token,
                    'created_at' => Carbon::now(),
                ]);
                $user->phone_verified = false;
                $user->phone_verification_string = $rand_str;
                if ( $user->save() ) {
                    activity()
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['phone_prefix' => $request->phone_prefix, 'phone' => $request->phone, 'phone_verification_string' => $rand_str])
                        ->log('Verification link sent to <b>:subject.phone_prefix :subject.phone</b>');
                    return SuccessResource::make(['message' => 'Phone verification link with OTP sent successfully!', 'otp' => $token]);
                } else {
                    throw new Exception("Error Processing Request");
                }
            } else {
                throw new Exception($res['msg']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }

    public function myActivities(Request $request)
    {
        try {
            $activity_duration = $request->activity_duration ? $request->activity_duration : '';
            $query = ActivityLog::where('causer_id', $request->user()->id);
            if ( $activity_duration == 'year' ) {
                $query->whereYear('created_at', date('Y'));
            } elseif ( $activity_duration == 'month' ) {
                $query->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'));
            } elseif ( $activity_duration == 'six_month' ) {
                $query->whereBetween('created_at', [Carbon::now()->subMonth(6), Carbon::now()]);
            } elseif ( $activity_duration == 'week' ) {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } else {
                $query->whereDate('created_at', Carbon::now());
            }
            $activity_data = $query->orderBy('created_at', 'DESC')->get()->groupBy(function ($item) {
                return $item->created_at->format('D d, M');
            });
            // $activities =   $query->orderBy('created_at','ASC')->get();

            $data = [];
            $count = 0;
            if ( !$activity_data->isEmpty() ) {
                foreach ($activity_data as $key => $activities) {
                    $data[$count]['date'] = $key;
                    foreach ($activities as $activity) {
                        $data[$count]['data'][] = $activity;
                    }
                    $count++;
                }
            }
            return ActivityResource::make($data);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data'    => null,
            ];
            return response()->json($response, 500);
        }
    }
}
