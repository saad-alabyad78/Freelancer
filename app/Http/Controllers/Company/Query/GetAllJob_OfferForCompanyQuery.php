<?php

namespace App\Http\Controllers\Company\Query;

use App\Models\Company;
use App\Models\JobRole;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
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
     * @authenticated
     * 
     * 
     * 
     * @apiResourceCollection App\Http\Resources\Company\Job_OfferMiniResource
     * @apiResourceModel App\Models\JobOffer paginate=20 with=App\Models\Company,App\Models\Skill,App\Models\JobRole
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
            ->when($filters['location_type']??false, function ($query, $locationType) {

                return $query->where('location_type', $locationType);

            })
            ->when($filters['attendence_type']??false, function ($query, $attendenceType) {

                return $query->where('attendence_type', $attendenceType);

            })->when($filters['status']??false, function ($query, $status) {

                return $query->where('status', $status);

            })->when($filters['job_role']??false , function($query , $job_role){

                $job_role = JobRole::where('name' , $job_role)->first() ;
                
                return $query->where('job_role_id' , $job_role->id);
                
            })
            ->with(['job_role' , 'skills' , 'company'])
            ->orderBy('created_at')
            ->paginate(20);

        
        //return for the company only

        return Job_OfferMiniResource::collection($job_offers) ;
    }
}
