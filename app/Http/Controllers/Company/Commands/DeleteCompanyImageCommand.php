<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Constants\Disks;
use App\Constants\Defaults;
use Illuminate\Http\Request;
use App\Services\imageService;
use App\Http\Controllers\Controller;

/**
 * @group Company Managment
 * 
 */
class DeleteCompanyImageCommand extends Controller
{
    private imageService $imageService ;
    public function __construct(imageService $_imageService)
    {
        $this->imageService = $_imageService ;
    }
    public function profile_image()
    {
        $company = Company::findOrFail(auth()->user()->role_id); 
        
        if($company->profile_image == Defaults::COMPANY_PROFILE_IMAGE){
            return response()->json([
                'message' => 'no image to delete' , 
            ] , 404);
        }

        $this->imageService->delete(Disks::COMPANY , $company->profile_image) ;
        $company->profile_image = null ;
        $company->save() ;
        
        return response()->json([
            'profile_image' => $company->profile_image ,
            'profile_image_url' => $company->profile_image_url ,
        ]);
    }public function background_image(Company $company)
    {
        $company = Company::findOrFail(auth()->user()->role_id); 
        
        if($company->background_image == Defaults::COMPANY_BACKGROUND_IMAGE){
            return response()->json([
                'message' => 'no image to delete' , 
            ] , 404);
        }

        $this->imageService->delete(Disks::COMPANY , $company->background_image) ;
        $company->background_image = null ;
        $company->save() ;
        
        return response()->json([
            'background_image' => $company->background_image ,
            'background_image_url' => $company->background_image_url ,
        ]);
    }


}
