<?php

namespace App\Http\Controllers\Company;

use App\Models\Image;
use App\Models\Company;
use App\Models\ContactLink;
use App\Models\CompanyPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\ICompanyRepository;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Requests\Company\CreateCompanyRequest;
use App\Http\Requests\Company\DeleteCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Requests\Company\CreateCompanyImageRequest;

/**
 * @group Company Managment
 * 
 */
class CompanyController extends Controller
{
    public function __construct(protected ICompanyRepository $companyRepository)
    {}

    /**
     * Store New Company .
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
    public function store(CreateCompanyRequest $request)
    {
        DB::beginTransaction();
        
        $data = $request->validated();

        try {
            //create company
            $company = $this->companyRepository->create($data) ;

            DB::commit() ;
        
            return CompanyResource::make($company->load([
                'contact_links'  ,
                'gallery_images' ,
                'company_phones' ,
                ]))->response()->setStatusCode(201) ;

        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'message' => 'something went wrong' ,
                'error' => $th->getMessage() 
                ] , 400) ;
        }
    }

    /**
     * Show Company .
     * 
     * 
     * @apiResource App\Http\Resources\Company\CompanyResource
     * @apiResourceModel App\Models\Company with=App\Models\ContactLink,App\Models\GalleryImage,App\Models\CompanyPhone
     * 
     * 
     * @return CompanyResource 
     * 
     */
    public function show(Company $company)
    {
        return CompanyResource::make($company->load([
            'contact_links'  ,
            'gallery_images' ,
            'company_phones' ,
            ])); 
    }
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
    public function update(UpdateCompanyRequest $request)
    {
        DB::beginTransaction() ;
        
        $data = $request->validated() ;

        try {
            $company = Company::findOrFail(auth()->user()->role['id']);

            //todo : delete the old images profile and background in the observer

            $company = $this->companyRepository->update($company , $data);
            
            DB::commit() ;
            
            return CompanyResource::make($company->load([
                'contact_links'  ,
                'gallery_images' ,
                'company_phones' ,
                ]))->response()->setStatusCode(200) ;
                
        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'message' => $th->getMessage() ,
                'trace' => $th->getTrace() ,
            ], 400) ;
        }
    }

    /**
     * Delete the company.
     * Note: the user will be deleted 
     * 
     * @authenticated
     * 
     * return 422 if password is incurrect
     * 
     *  @return \Illuminate\Http\Response | \Illuminate\Routing\ResponseFactory
     */
    public function delete(DeleteCompanyRequest $request)
    {
        $password = $request->validated()['password'] ;

        if(!Hash::check($password , auth()->user()->getAuthPassword())){
            return response()->json([
                'error' => 'wrong password'
            ] , 422 );
        }

        $company = Company::findOrFail(auth()->user()->role_id) ;

        $company->delete() ;

        return response()->noContent() ;
    }
}