<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Constants\Disks;
use App\Constants\Defaults;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\imageService;
use App\Constants\CloudFolders;
use App\Http\Controllers\Controller;
use App\Jobs\DeleteCloudinaryAssetsJob;
use App\Http\Requests\Company\CreateCompanyImageRequest;

/**
 * @group Company Managment
 * 
 */
class CreateCompanyImageCommand extends Controller
{
    public function profile_image(CreateCompanyImageRequest $request)
    {
        $company = Company::findOrFail(auth()->user()->role_id) ;
        
        //delete the old image 
        DeleteCloudinaryAssetsJob::dispatchIf(
            $company->profile_image_public_id != null ,
            [
                $company->profile_image_public_id ,
            ]
        ) ;
        

        $cloudinaryImage = $request->file('image')->storeOnCloudinary(CloudFolders::COMPANY) ;

        $company->update([
            'profile_image_url' => $cloudinaryImage->getSecurePath() ,
            'profile_image_public_id' => $cloudinaryImage->getPublicId() ,
        ]) ;

        return response()->json([
            'profile_image_url' => $company->profile_image_url ,
        ],201);
    }
    public function background_image(CreateCompanyImageRequest $request)
    {
        $company = Company::findOrFail(auth()->user()->role_id) ;
        
        //delete the old image 
        DeleteCloudinaryAssetsJob::dispatchIf(
            $company->background_image_public_id != null ,
            [
                $company->background_image_public_id ,
            ]
        ) ;
        

        $cloudinaryImage = $request->file('image')->storeOnCloudinary(CloudFolders::COMPANY) ;

        $company->update([
            'background_image_url' => $cloudinaryImage->getSecurePath() ,
            'background_image_public_id' => $cloudinaryImage->getPublicId() ,
        ]) ;

        return response()->json([
            'background_image_url' => $company->profile_image_url ,
        ],201);
    }
}
