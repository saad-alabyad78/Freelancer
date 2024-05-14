<?php

namespace App\Http\Controllers\Category\Skill\Query;

use App\Models\Skill;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Category\SkillRequest;
use App\Http\Resources\Category\SkillResource;
/**
 * @group Category Managment 
 **/
class SearchAllSkillQuery extends Controller
{
    /**
     * search throw all the skills.
     * 
     *@apiResource App\Http\Resources\Category\IndustryResource
     *@apiResourceModel App\Models\Industry
     * 
     *@return \Illuminate\Http\JsonResponse
     *
     */
    public function __invoke(SkillRequest $request)
    {
        $skill = Skill::where('name' , 'like' , '%'. $request->name .'%')->get() ;

        return SkillResource::collection($skill)
            ->response()->withHeaders(['Accept' => 'application/json']) ;
    }
}
