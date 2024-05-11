<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Constants\Disks;
use App\Constants\Defaults;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\imageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CreateCompanyImageRequest;

/**
 * @group Company Managment
 * 
 */
class CreateCompanyImageCommand extends Controller
{
    private imageService $imageService ;
    public function __construct(imageService $_imageService)
    {
        $this->imageService = $_imageService ;
    }
    public function profile_image(CreateCompanyImageRequest $request)
    {
        $company = Company::findOrFail(auth()->user()->role_id) ;
        
        //delete the old image 
        if($company->profile_image != Defaults::COMPANY_PROFILE_IMAGE){
            $this->imageService->delete(Disks::COMPANY , $company->profile_image) ;
        }

        $company->profile_image = $this->imageService->store_image($request->image ?? null , Disks::COMPANY) ;
        $company->save() ;

        return response()->json([
            'profile_image_url' => $company->profile_image_url ,
        ],201);
    }
    public function background_image(Company $company , CreateCompanyImageRequest $request)
    {
        //delete the old image 
        if($company->background_image != Defaults::COMPANY_BACKGROUND_IMAGE){
            $this->imageService->delete(Disks::COMPANY , $company->background_image) ;
        }

        $company->background_image = $this->imageService->store_image($request->image ?? null , Disks::COMPANY) ;
        $company->save() ;

        return response()->json([
            'background_image_url' => $company->background_image_url ,
        ],201);
    }
}
