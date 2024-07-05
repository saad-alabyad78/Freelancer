<?php

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Models\JobRole;
use App\Models\JobOffer;
use App\Models\Freelancer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Company\JobOfferResource;
use App\Http\Requests\Company\JobOffersForCompanyRequest;
use App\Http\Requests\Company\JobOfferForFreelancersRequest;

/**
 * @group Company Managment
 **/
class JobOfferQueryController extends Controller
{
    /**
     * 
     * list job offers (for company owner)
     * 
     * return all job offers for this company.
     * 
     * @authenticated
     * 
     * 
     * 
     * @apiResourceCollection App\Http\Resources\Company\JobOfferResource
     * @apiResourceModel App\Models\JobOffer paginate=20 with=App\Models\Company,App\Models\Skill,App\Models\JobRole
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * 
     */
    public function ForOwner(JobOffersForCompanyRequest $request)
    {
        
        $company = Company::findOrFail(auth()->user()->role_id) ;

        $filters = $request->validated();
        $filters['company_id'] = $company->id ;

        //filter scope
        $job_offers = JobOffer::filter($filters)
                    ->with(['job_role' , 'skills' , 'company'])
                    ->orderByDesc('created_at')
                    ->paginate(20);

        
        //return for the company only

        return JobOfferResource::collection($job_offers) ;
    }

     /**
     * 
     * list job offers (for freelancers)
     * 
     * return all job offers for freelancers screan.
     * 
     * @authenticated
     * 
     * 
     * 
     * @apiResourceCollection App\Http\Resources\Company\JobOfferResource
     * @apiResourceModel App\Models\JobOffer paginate=20 with=App\Models\Company,App\Models\Skill,App\Models\JobRole
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * 
     */
    public function ForFreelancer(JobOfferForFreelancersRequest $request)
    {
        //just cant see pending (handle it in form request)
        $filters = $request->validated() ; 

        $freelancer = Freelancer::findOrFail(auth()->user()->role_id)  ;
        
        $offers = JobOffer::filter($filters , $freelancer)
            ->with('job_role' , 'skills' , 'company' , 'skills.skillable') 
            ->orderByDesc('created_at')//todo : add order by scope 
            ->paginate(20) ;

        return JobOfferResource::collection($offers) ;
    }

     /**
     * 
     * list job offers (for all)
     * 
     * return all job offers for guests screan.
     * 
     * @unauthenticated
     * 
     * 
     * 
     * @apiResourceCollection App\Http\Resources\Company\JobOfferResource
     * @apiResourceModel App\Models\JobOffer paginate=20 with=App\Models\Company,App\Models\Skill,App\Models\JobRole
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * 
     */
    public function ForGuest()
    {

        $offers = JobOffer::
              with('job_role' , 'skills' , 'company' , 'skills.skillable') 
              ->orderByDesc('created_at')
              ->paginate(20) ;

        return JobOfferResource::collection($offers) ;
    }


}
