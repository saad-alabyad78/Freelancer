<?php

namespace App\Http\Controllers\Company\Query;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\imageService;
use App\Http\Controllers\Controller;

class CompanyImageQuery extends Controller
{
    private imageService $imageService = new imageService() ;
    private $disk = 'company' ;
    public function profile_image(Company $company)
    {
        return new Response(
            $this->imageService->get_image($this->disk , $company->profile_image) ,
            200 ,
            ['Content-Type' => $this->imageService->get_type($this->disk , $company->profile_image)] 
        );
    }
    public function background_image(Company $company)
    {
        return new Response(
            $this->imageService->get_image($this->disk , $company->background_image) ,
            200 ,
            ['Content-Type' => $this->imageService->get_type($this->disk , $company->background_image)] 
        );
    }
}
