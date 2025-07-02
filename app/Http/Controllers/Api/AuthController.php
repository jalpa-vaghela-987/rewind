<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLoginRequest;
use App\Http\Requests\RegisterStepFiveRequest;
use App\Http\Requests\RegisterStepFourRequest;
use App\Http\Requests\RegisterStepOneRequest;
use App\Http\Requests\RegisterStepSixRequest;
use App\Http\Requests\RegisterStepThreeRequest;
use App\Http\Requests\RegisterStepTwoRequest;
use App\Http\Requests\SocialLoginRequest;
use App\Http\Requests\UploadIdScanRequest;
use App\Http\Requests\UploadIncorporationDocRequest;
use App\Http\Requests\VerifyEmailOTPRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ForgotPasswordVerifyRequest;
use App\Http\Requests\ResendEmailOtpRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\API\UserResource;
use App\Mail\CodeVerifyMail;
use App\Mail\ForgotPasswordCode;
use App\Models\Company;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\UserToken;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * @author Moh Ashraf
     */
    public function registerStepOne(RegisterStepOneRequest $request)
    {
        try {
            $inputs = $request->only('email');
            $otp = rand(1000, 9999);
            $details = [
                "otp" => $otp,
            ];

            Mail::to($inputs['email'])->send(new CodeVerifyMail($details));
            $model = User::updateOrCreate($inputs,['otp'=>$otp]);
            $token = $model->createToken(config('app.name'))->plainTextToken;
            $model->token = $token;
            return UserResource::make($model)
                ->additional(['message' => 'Verification code sent successfully!']);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @author Moh Ashraf
     */
    public function resendEmailOtp(ResendEmailOtpRequest $request)
    {
        try {
            $model  =   User::find($request->user()->id);
            $otp    =   rand(1000, 9999);
            $details = [
                "otp" => $otp,
            ];

            Mail::to($model->email)->send(new CodeVerifyMail($details));
            $model->otp     =   $otp;
            if($model->save()){
                return SuccessResource::make(['message' => 'Verification code sent successfully!']);
            }else{
                throw new Exception("Error Processing Request");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @author Moh Ashraf
     */
    public function verifyEmailOtp(VerifyEmailOTPRequest $request)
    {
        try {
            $user                       =   User::where('email',$request->user()->email)->first();
            $user->otp                  =   null;
            $user->email_verified       =   true;
            $user->registration_step   =   ($user->registration_step <=2)?2: $user->registration_step;
            if ($user->save()) {
                return SuccessResource::make(['message' => 'OTP verified successfully!']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @author Moh Ashraf
     */
    public function registerStepTwo(RegisterStepTwoRequest $request)
    {
        try {
            $inputs = $request->only('password');
            $user   = $request->user();
            $update = User::where('email', $user->email)->update(['password' => Hash::make($inputs['password']),'registration_step'=>$user->registration_step<3?3:$user->registration_step]);
            if ($update) {
                return SuccessResource::make(['message' => 'Password saved successfully!']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @author Moh Ashraf
     */
    public function registerStepThree(RegisterStepThreeRequest $request)
    {
        try {
            $inputs = $request->only(['name', 'phone_prefix', 'phone']);
            $inputs['registration_step'] = $request->user()->registration_step<4?4:$request->user()->registration_step;
            $update = User::where('email', $request->user()->email)->update($inputs);
            if ($update) {
                $user = User::find($request->user()->id);
                return SuccessResource::make(['message' => 'User Details saved Successfully!']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @author Moh Ashraf
     */
    public function uploadIdScan(UploadIdScanRequest $request)
    {
        try {
            $full_name      =   $request->file('id_scan')->getClientOriginalName();
            $ext            =   pathinfo($full_name, PATHINFO_EXTENSION);
            $filename       =   'id_proof_' . time() . '.' . $ext;
            $path           =   'images/' . $request->user()->id;
            $request->file('id_scan')->storeAs($path, $filename, 'public');
            $id_scan_path   =   $path . '/' . $filename;
            $update         =   User::where('email', $request->user()->email)->update(['id_proof' => $id_scan_path]);
            if ($update) {
                return SuccessResource::make(['message' => 'ID scan uploaded successfully!']);
            } else {
                throw new Exception("Error Processing Request");
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @author Moh Ashraf
     */
    public function registerStepFour(RegisterStepFourRequest $request)
    {
        try {
            $inputs = $request->only(['name', 'field', 'registration_id']);
            $company = Company::where('user_id', $request->user()->id)->first();
            if (!$company) {
                $inputs['user_id'] = $request->user()->id;
                Company::create($inputs);
                $update = true;
            } else {
                $update = $company->update($inputs);
            }
            if ($update) {
                $step   =   $request->user()->registration_step<5?5:$request->user()->registration_step;
                User::where('id',$request->user()->id)->update(['registration_step'=>$step]);
                return SuccessResource::make(['message' => 'Company details saved Successfully!']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @author Moh Ashraf
     */
    public function uploadIncorporationDoc(UploadIncorporationDocRequest $request)
    {
        try {
            $full_name = $request->file('incorporation_document')->getClientOriginalName();
            $ext = pathinfo($full_name, PATHINFO_EXTENSION);
            $filename = 'incorporation_document_' . time() . '.' . $ext;
            $path = 'images/' . $request->user()->id;
            $request->file('incorporation_document')->storeAs($path, $filename, 'public');
            $incorporation_doc_url = $path . '/' . $filename;
            $company = $request->user()->company;
            $company->incorporation_doc_url = $incorporation_doc_url;
            $saved = $company->save();
            if (!$saved) {
                throw new Exception("Error Processing Request");
            }
            return SuccessResource::make(['message' => 'Corporation document uploaded successfully!']);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @author Moh Ashraf
     */
    public function registerStepFive(RegisterStepFiveRequest $request)
    {
        try {
            $user_inputs = $request->only(['street', 'city', 'country_id']);
            $user = User::where('email', $request->user()->email)->first();
            if(!$user->company){
                $user_inputs['registration_step']   =   null;
                $msg                                =   'User registered successfully!';
            }else{
                $user_inputs['registration_step']   =   6;
                $msg                                =   'User details saved successfully!';
            }
            $update =   $user->update($user_inputs);
            if ($update) {
                return SuccessResource::make(['message' => $msg]);
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @author Moh Ashraf
     */
    public function registerStepSix(RegisterStepSixRequest $request)
    {
        try {
            $company_inputs = $request->only(['street', 'city', 'country_id']);
            $company        =   Company::where('user_id', $request->user()->id)->first();
            $update         =   $company->update($company_inputs);
            if ($update) {
                User::where('id',$request->user()->id)->update(['registration_step'=>null]);
                return SuccessResource::make(['message' => 'User registered successfully!']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function login(CreateLoginRequest $request)
    {
        $token = $request->user()->createToken($request->email)->plainTextToken;

        return SuccessResource::make(['token' => $token])
            ->additional(['message' => 'Logged in successfully.']);
    }
    /**
     * @author Moh Ashraf
     */
    public function SocialLogin(SocialLoginRequest $request)
    {
        try {
            $message = 'User logged in successfully!';
            $provider = strtolower($request->type);
            $access_token = $request->access_token;
            // get the provider's user. (In the provider server)
            $socialUser     =   Socialite::driver($provider)->userFromToken($access_token);
            $user   =   User::where('email', $socialUser->email)->first();
            if (!$user) {
                $user = new User();
                $user->name = $socialUser->name;
                $user->email = $socialUser->email;
                $user->status = 1;
                $user->profile_photo_path = $socialUser->avatar;
                $user->email_verified = true;
                $user->save();
                $message = 'User registered successfully!';
            }
            if(!$user->hasRole('user') ){
                $user->assignRole(ROLE_USER);
            }
            if ($provider == User::TYPE_MICROSOFT) {
                $socialData = [
                    'user_id'=>$user->id,
                    'provider_type' => User::TYPE_MICROSOFT,
                    'provider_token' => $socialUser->token,
                    'refresh_token' => $socialUser->refreshToken
                ];
            } else{
                $socialData = [
                    'user_id'=>$user->id,
                    'provider_type' => $provider,
                ];
            }
            $socialAccount  =   SocialAccount::updateOrCreate([
                'provider_id' => $socialUser->id
            ], $socialData);
            
            $token = $user->createToken(config('app.name'))->plainTextToken;
            $user->token = $token;
            return UserResource::make($user)
                ->additional(['message' => $message]);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {

        $user = User::where('email', $request->email)->first();
        $otp = rand(1000, 9999);
        UserToken::updateOrInsert(['user_id' => $user->id],
            [
                'token' => $otp,
                'created_at' => Carbon::now(),
                'type' => 'forgot_password'
            ]);

        $userToken = UserToken::where('token', $otp)->first();
        Mail::to($request->email)->send(new ForgotPasswordCode($userToken));
        return SuccessResource::make(['message' => 'Reset password code has been send successfully.']);

    }

    public function forgotPasswordVerify(ForgotPasswordVerifyRequest $request){
        return SuccessResource::make(['message' => 'OTP verified successfully.']);
    }
    /**
     * @author Moh Ashraf
     *  
    */
    public function resetPassword(ResetPasswordRequest $request){
        try{
            $user       =   User::where('email',$request->email)->first();
            $userToken  =   UserToken::where('user_id',$user->id)
                            ->where('type','forgot_password')
                            ->where('token',$request->code)
                            ->first();
            // if (!Hash::check($request->new_password, $user->password)) {
                User::where('id', $userToken->user_id)->update([
                    'password' => Hash::make($request->confirm_password)
                ]);
                $userToken->delete();
                return SuccessResource::make(["message"=>"password reset successfully!"]);
            // } else {
            //     $response = [
            //         'message' => 'The new password must be diffrent from old password',
            //     ];
            //     return response()->json($response, 500);
            // }
        }catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
}
