<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\Skill;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Freelancer\FreelancerResource;
use App\Http\Requests\Freelancer\UpdateFreelancerRequest;
/**
 *@group Freelancer Managment 
 **/
class UpdateFreelancerCommand extends Controller
{
     /**
     * Update Freelancer .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Freelancer\FreelancerResource
     * @apiResourceModel App\Models\Freelancer with=App\Models\Skill,App\Models\JobRole
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function __invoke(UpdateFreelancerRequest $request)
    {
        $data = $request->validated() ;

        $freelancer = Freelancer::findOrFail(auth()->user()->role['id']) ;

        $freelancer->update($data) ;

        if(array_key_exists('skills' , $data))
        {
            $freelancer->skills()->detach() ;

            $skills = Skill::whereIn('name' , $data['skills'])->get() ;

            $freelancer->skills()->saveMany($skills) ;
        }

        return FreelancerResource::make($freelancer->load(['skills' , 'job_role']))
            ->response()
            ->withHeaders(['Content-Type' => 'application/json']) ;
    }
}
