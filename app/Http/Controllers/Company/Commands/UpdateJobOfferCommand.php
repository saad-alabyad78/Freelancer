<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Skill;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\Job_OfferResource;
use App\Http\Requests\Company\UpdateJobOfferRequest;

/**
 * @group Company Managment
 * 
 */
class UpdateJobOfferCommand extends Controller
{
    /**
     * Update Job Offer .
     * 
     * @authenticated
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function __invoke(UpdateJobOfferRequest $request)
    {
        $data = $request->validated() ;

        //chick wither this is his job offer or not 

        $job_offer = JobOffer::findOrFail($data['job_offer_id']);

        if($job_offer->company_id != auth()->user()->role['id']){
            return response()->json([
                'this is not your job offer !' , 
                422
            ]);
        }

        DB::beginTransaction();

        try {

            //update job offer
            $job_offer->update($data) ;

            //update relations (skills .. etc)
            $job_offer->skills()->detach() ;

            $skills = Skill::whereIn('name' , $data['skills'])->get() ;

            $job_offer->skills()->saveMany($skills) ;
            
            DB::commit();
            
            return Job_OfferResource::make($job_offer->load(['company' , 'skills' , 'job_role']))
                ->response()
                ->withHeaders(['Accept' => 'application/json']);

        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response('something went wrong' , 400) ;
        }
    }
}
