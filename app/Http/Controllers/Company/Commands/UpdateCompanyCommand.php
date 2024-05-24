<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Models\ContactLink;
use App\Models\CompanyPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Requests\Company\UpdateCompanyRequest;

/**
 * @group Company Managment
 * 
 */
class UpdateCompanyCommand extends Controller
{
    /**
     * Update Company .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Company\CompanyResource
     * @apiResourceModel App\Models\Company with=App\Models\ContactLink,App\Models\GalleryImage,App\Models\CompanyPhone
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function __invoke(UpdateCompanyRequest $request)
    {
        DB::beginTransaction() ;
        
        $data = $request->validated() ;

        try {
            $company = Company::findOrFail(auth()->user()->role['id']);

            $company->update($data) ;

            //create links 

            $company->contact_links()->delete() ;
            
            if(array_key_exists('contact_links' , $data))
            {   
                $contact_links = [] ;
            
                foreach($data['contact_links'] as $link_name)
                {
                    $contact_links[] = new ContactLink(['name' => $link_name]) ;
                }
                $company->contact_links()->saveMany($contact_links) ;
            }

            //create phones 

            $company->company_phones()->delete() ;
            
            if(array_key_exists('company_phones' , $data))
            {
                $company_phones = [] ;
            
                foreach($data['company_phones'] as $company_phone)
                {
                    $company_phones[] = new CompanyPhone(['number' => $company_phone]) ;
                }
                $company->company_phones()->saveMany($company_phones) ;

            }
        
            DB::commit() ;
            
            return CompanyResource::make($company->load([
                'contact_links'  ,
                'gallery_images' ,
                'company_phones' ,
                ]))->response()->setStatusCode(200) ;
                
        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response('something went wrong' , 400) ;
        }

        
    }
}
