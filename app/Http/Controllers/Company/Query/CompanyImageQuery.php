<?php

namespace App\Http\Controllers\Company\Query;

use App\Models\Company;
use App\Constants\Disks;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\imageService;
use App\Http\Controllers\Controller;

class CompanyImageQuery extends Controller
{
    private imageService $imageService  ;

    public function __construct(imageService $_imageService)
    {
        $this->imageService = $_imageService ;
    }

    public function profile_image(Company $company)
    {
        return new Response(
            $this->imageService->get_image(Disks::COMPANY , $company->profile_image) ,
            200 ,
            ['Content-Type' => $this->imageService->get_type(Disks::COMPANY , $company->profile_image)] 
        );
    }
    public function background_image(Company $company)
    {
        return new Response(
            $this->imageService->get_image(Disks::COMPANY, $company->background_image) ,
            200 ,
            ['Content-Type' => $this->imageService->get_type(Disks::COMPANY , $company->background_image)] 
        );
    }
}
