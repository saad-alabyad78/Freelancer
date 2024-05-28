<?php

namespace App\Http\Controllers\Category\Skill\Query;

use App\Models\Skill;
use App\Models\JobRole;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Category\SkillRequest;
use App\Http\Resources\Category\SkillResource;
use Illuminate\Contracts\Database\Query\Builder;
/**
 * @group Category Managment 
 **/
class SearchAllSkillQuery extends Controller
{
    /**
     * search throw all the skills.
     * 
     *@apiResourceCollection App\Http\Resources\Category\IndustryResource
     *@apiResourceModel App\Models\Industry
     * 
     *@return \Illuminate\Http\JsonResponse
     *
     */
    public function __invoke(SkillRequest $request)
    {
        $name = $request->input('name') ;
        $job_role = $request->input('job_role') ;

      
        $skills = DB::table('skills')
        ->when($job_role , function(Builder $query , string $job_role){
            //return skills->job_roles
            $query->join('skillables' , 'skills.id' , '=' , 'skillables.skill_id')
                ->join('job_roles', function ($join) use ($job_role) {
                    $join->on('skillables.skillable_id', '=', 'job_roles.id')
                        ->where('skillables.skillable_type', '=', JobRole::class)
                        ->where('job_roles.name', '=', $job_role);
                })->select(['skills.id' , 'skills.name']);
        })
        ->when($name , function(Builder $query , string $name){
            $query->where('skills.name' , 'like' , "%{$name}%");
        })
        ->limit(100)
        ->get();

        return SkillResource::collection($skills)
            ->response()->withHeaders(['Accept' => 'application/json']) ;
    }
}
