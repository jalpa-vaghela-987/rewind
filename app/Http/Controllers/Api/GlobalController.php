<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\CountryResource;
use App\Http\Resources\API\ProjectTypeResource;
use App\Models\Country;
use App\Models\ProjectType;
use Exception;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    /**
     * @author Moh Ashraf
    */
    public function CountryList(Request $request){
        try{
            $countries    =   Country::where('is_active',1)->get();
            return CountryResource::collection($countries);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function ProjectList(Request $request){
        try{
            $projectTypes    =   ProjectType::where('is_active',1)->get();
            return ProjectTypeResource::collection($projectTypes);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

}
