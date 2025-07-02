<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteCertificateRequest;
use App\Http\Resources\API\CertificateResource;
use App\Http\Resources\API\SellCertificateResource;
use App\Http\Resources\SuccessResource;
use App\Models\Certificate;
use App\Models\SellCertificate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyPortfolioController extends Controller
{
    /**
     * @author Moh Ashraf
     */
    public function myPortfolio(Request $request){
        try{
            $per_page           =   $request->per_page?$request->per_page:10;
            $userId = $request->user()->id;
            $certificates = SellCertificate::with('certificate')
                ->where('user_id', $userId)
                ->whereHas("certificate",function($q){
                    $q->where("deleted_at", null);
                })
                ->where('remaining_units', '>', 0)
                ->orderBy('id', 'desc')
                ->paginate($per_page);


            $totalValueRecord   =   SellCertificate::select(DB::raw('SUM(price_per_unit*remaining_units) as totalValue'))->with('certificate')
                ->where('user_id', $userId)
                ->where('remaining_units','>',0)
                ->first();

            $totalValue   =   $totalValueRecord->totalValue;
            return SellCertificateResource::collection($certificates)->additional(['total_value'=>$totalValue]);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }

    }
    public function deleteCertificate(DeleteCertificateRequest $request){
        try{
            $certificate    =   Certificate::find($request->id);
            $certificate_   =   $certificate;
            if($certificate->delete()){
                activity()
                ->performedOn($certificate_)
                ->causedBy(auth()->user())
                ->log('certificate <b>:subject.name</b> has been deleted');
                return SuccessResource::make(['message'=>'Certificate deleted successfully']);
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
}
