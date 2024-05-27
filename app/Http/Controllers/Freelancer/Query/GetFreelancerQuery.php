<?php

namespace App\Http\Controllers\Freelancer\Query;

use App\Models\Freelancer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Freelancer\FreelancerResource;
/**
 *@group Freelancer Managment 
 **/
class GetFreelancerQuery extends Controller
{
    /**
     * Get Freelancer .
     * 
     * 
     * @apiResource App\Http\Resources\Freelancer\FreelancerResource
     * @apiResourceModel App\Models\Freelancer with=App\Models\Skill,App\Models\JobRole
     * 
     * 
     * @return \Illuminate\Http\JsonResponse 
     * 
     */
    public function __invoke(Freelancer $freelancer)
    {
        return FreelancerResource::make($freelancer->load(['skills' , 'job_role']))
            ->response()->setStatusCode(200);
    }
}
