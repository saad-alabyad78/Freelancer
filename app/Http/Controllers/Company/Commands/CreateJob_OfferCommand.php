<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Skill;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Industry;
use App\Models\JobOffer;
use App\Constants\Job_OfferStatus;
use App\Rules\Job_OfferStatusRule;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\Job_OfferResource;
use App\Http\Requests\Company\CreateJob_OfferRequest;
/**
 * @group Company Managment
 * 
 **/
class CreateJob_OfferCommand extends Controller
{
    /**
     * create job offer.
     */
    public function __invoke(CreateJob_OfferRequest $request)
    {
        $data = $request->validated() ;
        
        $job_offer = JobOffer::Create(
            [
                'status' => Job_OfferStatus::PENDING ,
                'type' => $data['type'] ,
                'max_salary' => $data['max_salary'] ?? null ,
                'min_salary' => $data['min_salary'] ?? null ,
                'transportation' => $data['transportation'] ,
                'health_insurance' => $data['health_insurance'] ,
                'military_service' => $data['military_service'] ,
                'max_age' => $data['max_age'] ?? null ,
                'min_age' => $data['min_age'] ?? null ,
                'gender' => $data['gender'] ?? null ,
                'description' => $data['description'],

                'industry_name' => $data['industry_name'] ,
                'company_id' => $data['company_id'] ,
                'job_role_id' => $data['job_role_id'] ,
            ]
        );

        
        $skills = Skill::whereIn('name' , $data['skills'])->get() ;

        $job_offer->skills()->saveMany($skills) ;
        
        return Job_OfferResource::make($job_offer->load(['company' , 'skills' , 'job_role']))
              ->response()
              ->withHeaders(['Accept' => 'application/json']);
    }
}
