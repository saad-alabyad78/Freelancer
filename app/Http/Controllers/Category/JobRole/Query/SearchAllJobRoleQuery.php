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
     */
    public function __invoke(GetAllJobRolesRequest $request)
    {
        $job_roles = JobRole::where('name' , 'like' , '%'.$request->name.'%')->get() ;

        return Job_RoleResource::collection($job_roles)
            ->response()
            ->withHeaders(['Accept' => 'application/json']) ;
    }
}