<?php

namespace App\Http\Controllers\Category;

use App\Models\JobRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\JobRoleResource;
use App\Http\Requests\Category\JobRoleSearchRequest;
use App\Http\Requests\Category\JobRoleChunkInsertRequest;
/**
 * @group Category Managment
 * 
 **/
class JobRoleController extends Controller
{
    /**
     * search for the job role 
     * 
     * @apiResourceCollection App\Http\Resources\Category\JobRoleResource
     * @apiResourceModel App\Models\JobRole
     * 
     */
    public function search(JobRoleSearchRequest $request)
    {
        $name = $request->input('name') ;
        //to do: cache and scout search
        $job_roles = JobRole::where('name' , 'like' , '%'.$name.'%')
        ->limit(100)
        ->get() ;

        return JobRoleResource::collection($job_roles);
    }
    public function chunkInsert(JobRoleChunkInsertRequest $request)
    {     
        JobRole::insertOrIgnore($request->validated()) ;
    }
}
