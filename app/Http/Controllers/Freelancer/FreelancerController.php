<?php

namespace App\Http\Controllers\Freelancer;

use App\Models\Image;
use App\Models\Skill;
use App\Models\Skillable;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Freelancer\FreelancerResource;
use App\Http\Requests\Freelancer\CreateFreelancerRequest;
use App\Http\Requests\Freelancer\UpdateFreelancerRequest;
/**
 *@group Freelancer Managment 
 *
 **/
class FreelancerController extends Controller
{
    /**
     * Get Freelancer .
     * 
     * 
     * @apiResource App\Http\Resources\Freelancer\FreelancerResource
     * @apiResourceModel App\Models\Freelancer with=App\Models\Skill,App\Models\JobRole
     * 
     * 
     * @return \App\Http\Resources\Freelancer\FreelancerResource
     * 
     */
    public function show(Freelancer $freelancer)
    {
        return FreelancerResource::make($freelancer->load([
            'skills' ,
            'job_role'  ,
            'portfolios.files' ,
            'portfolios.skills' ,
            'portfolios.images' ,
            ])) ;
    }
    /**
     * Store New Freelancer .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Freelancer\FreelancerResource
     * @apiResourceModel App\Models\Freelancer with=App\Models\Skill,App\Models\JobRole
     * 
     * 
     * @return \App\Http\Resources\Freelancer\FreelancerResource
     * 
     */
    public function store(CreateFreelancerRequest $request)
    {
        DB::beginTransaction();
        
        $data = $request->validated() ;

        $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        $data['background_image_url'] = Image::findOrFail($data['background_image_id'])->first();
        
        $data['username'] = auth()->user()->slug ;

        try {
            //create company
            $freelancer = Freelancer::create($data);

            $freelancer->user()->save(auth()->user()) ;

            $skillables = array_map(function($item) use ($freelancer){
                return [
                    'skill_id' => $item ,
                    'skillable_id' => $freelancer->id ,
                    'skillable_type' => Freelancer::class ,
                ] ;
            } , $data['skill_ids']);
            
            Skillable::insert($skillables) ;

            DB::commit() ;
            
            return FreelancerResource::make($freelancer->load('skills' , 'job_role'));
            
        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'message' => 'something went wrong' ,
                'error' => $th->getMessage() 
                ] , 400) ;
        }
    }
     /**
     * update Freelancer .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Freelancer\FreelancerResource
     * @apiResourceModel App\Models\Freelancer with=App\Models\Skill,App\Models\JobRole
     * 
     * 
     * @return \App\Http\Resources\Freelancer\FreelancerResource
     * 
     */
    public function update(UpdateFreelancerRequest $request)
    {
        $data = $request->validated() ;

        $freelancer = Freelancer::findOrFail(auth()->user()->role['id']) ;

        $data = $request->validated() ;

        if($data['profile_image_id'] ?? false and $freelancer?->profile_image_id ?? false)
        {
            Image::where('id' , $freelancer->profile_image_id)->update([
                'deleted' => true ,
                'imagable_id' => $freelancer->id ,
                'imagable_type' => freelancer::class ,
            ]);
            $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        }
        if($data['background_image_id'] ?? false and $freelancer?->background_image_id ?? false)
        {
            Image::where('id' , $freelancer->background_image_id)->update([
                'deleted' => true ,
                'imagable_id' => $freelancer->id ,
                'imagable_type' => freelancer::class ,
            ]);
            $data['background_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        }

        $freelancer->update($data) ;

        if(array_key_exists('skills' , $data))
        {
            $freelancer->skills()->detach() ;

            $skills = Skill::findMany($data['skill_ids']) ;

            $freelancer->skills()->saveMany($skills) ;
        }

        return FreelancerResource::make($freelancer->load(['skills' , 'job_role']));
    }
}
