<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddBankAccountRequest;
use App\Http\Requests\AddCreditCardRequest;
use App\Http\Requests\BankAccountDetailRequest;
use App\Http\Requests\CardDetailRequest;
use App\Http\Requests\DeleteBankAccountRequest;
use App\Http\Requests\DeleteCreditCardRequest;
use App\Http\Requests\setPrimaryBankRequest;
use App\Http\Requests\SetPrimaryCardRequest;
use App\Http\Resources\API\BankDetailResource;
use App\Http\Resources\API\CardDetailResource;
use App\Http\Resources\SuccessResource;
use App\Models\BankDetail;
use App\Models\CardDetail;
use Exception;
use Illuminate\Http\Request;

class ManagePaymentController extends Controller
{
    /**
     * @author Moh Ashraf
     */
    public function addCreditCard(AddCreditCardRequest $request){
        try{
            $inputs             =   $request->only(['card_no','card_holder_name','expiry_month','expiry_year','cvv','is_primary']);
            $inputs['user_id']  =   auth()->user()->id;
            if(!auth()->user()->creditCard){
                $inputs['is_primary']  =   true;
            }elseif($inputs['is_primary']){
                CardDetail::where('user_id',$inputs['user_id'])->update(['is_primary'=>0]);
            }
            $saved              =   CardDetail::create($inputs);
            if($saved){
                activity()
                ->performedOn($saved)
                ->causedBy(auth()->user())
                ->log('Card details added successfully');
                return SuccessResource::make(['message' => 'Card details added successfully!']);
            }else{
                throw new Exception("Error Processing Request");
            }
        }catch(Exception $e){
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
    public function setPrimaryCard(SetPrimaryCardRequest $request){
        try{
            $unset_primary      =   CardDetail::where('user_id',$request->user()->id)->update(['is_primary'=>0]);
            $card               =   CardDetail::where('id',$request->id)->first();
            $card->is_primary   =   true;
            if($card->save()){
                activity()
                ->performedOn($card)
                ->causedBy($request->user())
                ->log('Card is set to primary successfully');
                return SuccessResource::make(['message' => 'Card is set to primary successfully!']);
            }else{
                throw new Exception("Error Processing Request");
            }
        }catch(Exception $e){
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
    public function addBankAccount(AddBankAccountRequest $request){
        try{
            $inputs             =   $request->only(['name','iban','beneficiary_name','bic','country_id']);
            $inputs['user_id']  =   auth()->user()->id;
            if(!auth()->user()->bankAccount){
                $inputs['is_primary']  =   true;
            }
            $saved              =   BankDetail::create($inputs);
            if($saved){
                activity()
                ->performedOn($saved)
                ->causedBy(auth()->user())
                ->log('Bank details with <br>IBAN :subject.iban </br>has been Saved');
                return SuccessResource::make(['message' => 'Bank details added successfully!']);
            }else{
                throw new Exception("Error Processing Request");
            }
        }catch(Exception $e){
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
    public function setPrimaryBank(setPrimaryBankRequest $request){
        try{
            $unset_primary      =   BankDetail::where('user_id',$request->user()->id)->update(['is_primary'=>0]);
            $bank               =   BankDetail::where('id',$request->id)->first();
            $bank->is_primary   =   true;
            if($bank->save()){
                activity()
                ->performedOn($bank)
                ->causedBy($request->user())
                ->log('Bank with <br>IBAN :subject.iban </br> is set to primary successfully');
                return SuccessResource::make(['message' => 'Bank is set to primary successfully!']);
            }else{
                throw new Exception("Error Processing Request");
            }
        }catch(Exception $e){
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
    public function deleteBankAccount(DeleteBankAccountRequest $request){
        try{
            $bank               =   BankDetail::where('id',$request->id)->first();
            $performedOn        =   $bank;
            if($bank && $bank->delete()){
                activity()
                ->performedOn($performedOn)
                ->causedBy($request->user())
                ->log('Bank with <br>IBAN :subject.iban </br> is deleted');
                return SuccessResource::make(['message' => 'Bank account is deleted successfully!']);
            }else{
                throw new Exception("Error Processing Request");
            }
        }catch(Exception $e){
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
    public function deleteCreditCard(DeleteCreditCardRequest $request){
        try{
            $card               =   CardDetail::where('id',$request->id)->first();
            $performedOn        =   $card;
            if($card && $card->delete()){
                activity()
                ->performedOn($performedOn)
                ->causedBy($request->user())
                ->log('Card deleted successfully');
                return SuccessResource::make(['message' => 'Card deleted successfully!']);
            }else{
                throw new Exception("Error Processing Request");
            }
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }   
    }
    public function getMyBankAccountList(Request $request){
        try{
            $records    =  BankDetail::where('user_id',auth()->user()->id)->get();
            return BankDetailResource::make($records);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function getMyBankAccountDetail(BankAccountDetailRequest $request){
        try{
            $record    =  BankDetail::where('user_id',auth()->user()->id)->where('id',$request->bank_id)->first();
            if($record){
                return BankDetailResource::make($record);
            }else{
                throw new Exception("Error Processing Request");
            }
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function getMyCardList(Request $request){
        try{
            $records    =  CardDetail::where('user_id',auth()->user()->id)->get();
            return CardDetailResource::make($records);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function getMyCardDetail(CardDetailRequest $request){
        try{
            $record    =  CardDetail::where('user_id',auth()->user()->id)->where('id',$request->card_id)->first();
            if($record){
                return CardDetailResource::make($record);
            }else{
                throw new Exception("Error Processing Request");
            }
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function getMyPrimaryCard(Request $request){
        try{
            $record    =  auth()->user()->creditCard;
            if($record){
                return CardDetailResource::make($record);
            }else{
                return response()->json(['message'=>'Card detail not found'], 404);
            }
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function getMyPrimaryBank(Request $request){
        try{
            $record    =  auth()->user()->bankAccount;
            if($record){
                return BankDetailResource::make($record);
            }else{
                return response()->json(['message'=>'Bank detail not found'], 404);
            }
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
}