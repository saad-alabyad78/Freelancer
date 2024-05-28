<?php

namespace App\Http\Controllers\Category\JobRole\Query;

use App\Models\JobRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\Job_RoleResource;
use App\Http\Requests\Category\GetAllJobRolesRequest;
/**
 * @group Category Managment 
 **/
class SearchAllJobRoleQuery extends Controller
{
    /**
     * search for the job role 
     * 
     * @apiResourceCollection App\Http\Resources\Category\Job_RoleResource
     * @apiResourceModel App\Models\JobRole
     * 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function __invoke(GetAllJobRolesRequest $request)
    {
        $job_roles = JobRole::where('name' , 'like' , '%'.$request->name.'%')
        ->limit(100)
        ->get() ;

        return Job_RoleResource::collection($job_roles)
            ->response()
            ->withHeaders(['Accept' => 'application/json']) ;
    }
}
