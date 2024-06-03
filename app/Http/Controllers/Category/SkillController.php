<?php

namespace App\Http\Controllers\Category;

use App\Models\Skill;
use App\Models\JobRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use App\Http\Resources\Category\SkillResource;
use App\Http\Requests\Category\SkillSearchRequest;
/**
 * @group Category Managment
 * 
 **/
class SkillController extends Controller
{
    /**
     * search skill
     * get first 100 match
     * 
     * 
     * @apiResourceCollecton App\Http\Resources\Category\skillResource
     * @apiResourceModel App\Models\skill
     * 
     */
    public function search(SkillSearchRequest $request)
    {
        //TO DO:search scout and cache

        $name = $request->input('name') ;
        $job_role_id = $request->input('job_role_id') ;

      
        $skills = DB::table('skills')
        ->when($job_role_id , function(Builder $query , string $job_role_id){
            //return skills->job_roles
            $query->join('skillables' , 'skills.id' , '=' , 'skillables.skill_id')
                  ->join('job_roles', function ($join) use ($job_role_id) {
                    $join->on('skillables.skillable_id', '=', 'job_roles.id')
                        ->where('skillables.skillable_type', '=', JobRole::class)
                        ->where('job_roles.id', '=', $job_role_id);
                })->select(['skills.id' , 'skills.name']);
        })
        ->when($name , function(Builder $query , string $name){
            $query->where('skills.name' , 'like' , "%{$name}%");
        })
        ->limit(100)
        ->get();
        
        return SkillResource::collection($skills) ;
    }
}
