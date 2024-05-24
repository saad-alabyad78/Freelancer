<?php

namespace App\Http\Controllers\Company\Query;

use App\Models\Company;
use App\Models\JobRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\Job_OfferMiniResource;
use App\Http\Requests\Company\jobOffersForCompanyRequest;

/**
 * @group Company Managment
 **/
class GetAllJob_OfferForCompanyQuery extends Controller
{
    /**
     * 
     * search job offers
     * 
     * return all job offers for this company.
     * 
     * 
     * @apiResourceCollection App\Http\Resources\Company\Job_OfferMiniResource
     * @apiResourceModel App\Models\JobOffer with=App\Models\Company,App\Models\Skill,App\Models\JobRole
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * 
     */
    public function __invoke(jobOffersForCompanyRequest $request)
    {
        $company = Company::findOrFail(auth()->user()->role_id) ;

        $filters = $request->validated();

        //filter scope
        $job_offers = $company->job_offers()
            ->when($filters['location_type'], function ($query, $filters) {

                return $query->where('location_type', $filters['location_type']);

            })
            ->when($filters['attendence_type'], function ($query, $filters) {

                return $query->where('attendence_type', $filters['attendence_type']);

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
