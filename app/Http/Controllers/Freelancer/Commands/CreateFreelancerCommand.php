<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\Skill;
use App\Models\Freelancer;
use App\Constants\CloudFolders;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Freelancer\FreelancerResource;
use App\Http\Requests\Freelancer\CreateFreelancerRequest;
/**
 *@group Freelancer Managment 
 **/
class CreateFreelancerCommand extends Controller
{
    /**
     * Store New Freelancer .
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
    public function __invoke(CreateFreelancerRequest $request)
    {
        DB::beginTransaction();
        
        $data = $request->validated();

        try {
            
            $s = microtime(true) ;
            $cloudinaryImage = $request->file('profile_image')?->storeOnCloudinary(CloudFolders::FREELANCER) ?? null ;
                $p_url = $cloudinaryImage?->getSecurePath() ?? null ;
                $p_id = $cloudinaryImage?->getPublicId() ?? null  ;
            $cloudinaryImage = $request->file('background_image')?->storeOnCloudinary(CloudFolders::FREELANCER) ?? null ;
                $b_url = $cloudinaryImage?->getSecurePath() ?? null ;
                $b_id = $cloudinaryImage?->getPublicId() ?? null ;
            $e = microtime(true) ;
       
            
            
            //create company
            $freelancer = Freelancer::create([
                    'profile_image_url' =>  $p_url ,
                    'profile_image_public_id' => $p_id ,

                    'background_image_url' =>  $b_url ,
                    'background_image_public_id' =>  $b_id ,
                    
                    'username' => auth()->user()->slug ,
                    'headline' => $data['headline'] ,
                    'description' => $data['description'] ,
                    'city' => $data['city'] , 
                    'date_of_birth' => $data['date_of_birth'] ,
                    'gender' => $data['gender'] ,

                    'job_role_id' => $data['job_role_id'] ,
            ]);

            
            
            $freelancer->user()->save(auth()->user()) ;

            $skills = Skill::whereIn('name' , $data['skills'])->get() ;
            $freelancer->skills()->saveMany($skills) ;

            DB::commit() ;
            
            return FreelancerResource::make($freelancer->load('skills' , 'job_role'))
                ->response()
                ->setStatusCode(201)
                ->withHeaders(['Content-Type' => 'application/json']);

        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'message' => 'something went wrong' ,
                'error' => $th->getMessage() 
                ] , 400) ;
        }
    }
}
