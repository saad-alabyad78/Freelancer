<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Constants\Disks;
use App\Constants\Defaults;
use Illuminate\Http\Request;
use App\Services\imageService;
use App\Http\Controllers\Controller;
use App\Jobs\DeleteCloudinaryAssetsJob;

/**
 * @group Company Managment
 * 
 */
class DeleteCompanyImageCommand extends Controller
{
    public function profile_image()
    {
        $company = Company::findOrFail(auth()->user()->role_id); 
        
        if($company->profile_image_public_id == null){
            return response()->json([
                'message' => 'no image to delete' , 
            ] , 404);
        }

        DeleteCloudinaryAssetsJob::dispatchAfterResponse([
            $company->profile_image_public_id 
        ]);
        
        $company->update([
            'profile_image_public_id' => null ,
            'profile_image_url' => null ,
        ]); ;
        
        return response()->json([
            'profile_image_url' => $company->profile_image_url ,
        ]);
    }public function background_image(Company $company)
    {
        $company = Company::findOrFail(auth()->user()->role_id); 
        
        if($company->background_image_public_id == null){
            return response()->json([
                'message' => 'no image to delete' , 
            ] , 404);
        }

        DeleteCloudinaryAssetsJob::dispatchAfterResponse([
            $company->background_image_public_id ,
        ]);

        $company->update([
            'background_image_url' => null ,
        ]);
        
        return response()->json([
            'background_image_url' => $company->background_image_url ,
        ]);
    }


}
