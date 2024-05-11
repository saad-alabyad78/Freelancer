<?php

namespace App\Http\Controllers\Company\Query;

use App\Models\Company;
use App\Models\JobRole;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\Job_OfferResource;
use App\Http\Resources\Company\Job_OfferMiniResource;
use App\Http\Requests\Company\jobOffersForCompanyRequest;

/**
 * @group Company Managment
 **/
class GetAllJob_OfferQueryForCompany extends Controller
{
    public function __invoke(Company $company, jobOffersForCompanyRequest $request)
    {
        $filters = $request->validated();

        //filter scope
        $job_offers = $company->job_offers()
            ->when($filters['type'], function ($query, $filters) {

                return $query->where('type', $filters['type']);

            })->when($filters['status'], function ($query, $filters) {

                return $query->where('status', $filters['status']);

            })->when($filters['job_role'] , function($query , $filters){

                $job_role = JobRole::where('name' , $filters['job_role'])->first() ;
                
                return $query->where('job_role_id' , $job_role->id);
                
            })
            ->with(['job_role' , 'skills' , 'company'])
            ->orderBy('created_at')
            ->paginate();

        
        //return for the company only

        return Job_OfferMiniResource::collection($job_offers) ;
    }
}
