<?php

namespace App\Http\Controllers\Company;

use App\Models\Skill;
use App\Models\JobOffer;
use App\Models\Skillable;
use Illuminate\Http\Request;
use App\Constants\JobOfferStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\JobOfferResource;
use App\Http\Requests\Company\CreateJobOfferRequest;
use App\Http\Requests\Company\DeleteJobOfferRequest;
use App\Http\Requests\Company\UpdateJobOfferRequest;
/**
 * @group Company Managment
 *
 **/
class JobOfferController extends Controller
{
    /**
     * create job offer.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Company\JobOfferResource
     * @apiResourceModel App\Models\JobOffer with=App\Models\Company,App\Models\Skill,App\Models\JobRole
     *
     * @return \App\Http\Resources\Company\JobOfferResource
     *
     */
    public function store(CreateJobOfferRequest $request)
    {
<<<<<<< Updated upstream
        return $data = $request->validated() ;
            
=======
        $data = $request->validated() ;

>>>>>>> Stashed changes
        $data['status'] = JobOfferStatus::PENDING  ;
        $data['company_id'] = auth()->user()->role['id'] ;

        $job_offer = JobOffer::Create($data);

        $skillables = array_map(function($item) use ($job_offer){
            return [
                'skill_id' => $item['id'] ,
                'skillable_id' => $job_offer->id ,
                'skillable_type' => JobOffer::class ,
                'required' => $item['required'] ,
            ] ;
        } , $data['skill_ids']);

        Skillable::insert($skillables) ;

        return JobOfferResource::make($job_offer->load(['company' , 'skills' , 'job_role']));
    }
     /**
     * Update Job Offer .
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Company\JobOfferResource
     * @apiResourceModel App\Models\JobOffer with=App\Models\Company,App\Models\Skill,App\Models\JobRole
     *
     * @return \App\Http\Resources\Company\JobOfferResource
     *
     */
    public function update(UpdateJobOfferRequest $request)
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
            if(array_key_exists('skills' , $data))
            {
                $job_offer->skills()->detach() ;

                $skills = Skill::findMany($data['skills_ids']) ;

                $job_offer->skills()->saveMany($skills) ;
            }

            DB::commit();

            return JobOfferResource::make($job_offer->load(['company' , 'skills' , 'job_role']));

        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'message' => $th->getMessage() ,
                'trace' => $th->getTrace()
                 ] , 400) ;
        }
    }
    /**
     * Delete Job Offer .
<<<<<<< Updated upstream
     * 
=======
     *
>>>>>>> Stashed changes
     * @authenticated
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function delete(DeleteJobOfferRequest $request)
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
        if($job_offer->status == JobOfferStatus::AVTIVE){
            return response()->json([
                'you can\' delete active job offer' ,
                422
            ]);
        }

        $job_offer->delete() ;

        return response()->noContent();
    }

}
