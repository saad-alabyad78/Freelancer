<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Skill;
use App\Models\Company;
use App\Models\Industry;
use App\Models\Job_Role;
use App\Models\Job_Offer;
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
    public function __invoke(Company $company ,Industry $industry , CreateJob_OfferRequest $request)
    {
        $data = $request->validated() ;

        $job_role_id = Job_Role::where('name' , $data['job_role'])
            ->pluck('id')
            ->firstOrFail();
        
        $job_offer = Job_Offer::Create(
            [
                'status' => Job_OfferStatus::PENDING ,
                'type' => $data['type'] ,
                'max_sallary' => $data['max_salary'] ,
                'min_salary' => $data['min_salary'] ,
                'transportation' => $data['transportation'] ,
                'health_insurance' => $data['health_insurance'] ,
                'military_service' => $data['military_service'] ,
                'max_age' => $data['max_age'] ,
                'min_age' => $data['min_age'] ,
                'gender' => $data['gender'] ,
                'description' => $data['description'],

                'industry_name' => $industry->name ,
                'company_id' => $company->id ,
                'job_role_id' => $job_role_id ,
            ]
        );

        //skills
        
        $skills = Skill::whereIn('name' , $data['skills'])->get() ;

        $job_offer->skills()->saveMany($skills) ;

        return Job_OfferResource::make($job_offer->load(['company' , 'skills' , 'job_role']));
    }
}
