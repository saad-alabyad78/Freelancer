<?php

namespace App\Http\Controllers\Company\Query;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\CompanyResource;

/**
 * @group Company Managment
 **/
class GetCompanyQuery extends Controller
{
    /**
     * Get Company .
     * 
     * 
     * @apiResource App\Http\Resources\Company\CompanyResource
     * @apiResourceModel App\Models\Company with=App\Models\ContactLink,App\Models\GalleryImage,App\Models\CompanyPhone
     * 
     * 
     * @return \Illuminate\Http\JsonResponse 
     * 
     */
    public function __invoke(Company $company)
    {
        return CompanyResource::make($company->load([
            'contact_links'  ,
            'gallery_images' ,
            'company_phones' ,
            ]))->response()->setStatusCode(200) ;
    }
}
