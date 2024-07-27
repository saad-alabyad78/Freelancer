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
use App\Interfaces\IFreelancerRepository;
use App\Http\Resources\Freelancer\FreelancerResource;
use App\Http\Requests\Freelancer\CreateFreelancerRequest;
use App\Http\Requests\Freelancer\UpdateFreelancerRequest;
/**
 *@group Freelancer Managment
 *
 **/
class FreelancerController extends Controller
{
    public function __construct(protected IFreelancerRepository $freelancerRepository)
    {

    }
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
            // 'skills' ,
            // 'job_role'  ,
            // 'portfolios.files' ,
            // 'portfolios.skills' ,
            // 'portfolios.images' ,
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

        try {

            $freelancer = $this->freelancerRepository->create($data);

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

        $freelancer = Freelancer::findOrFail(auth('sanctum')->user()->role['id']) ;

        $freelancer = $this->freelancerRepository->update($freelancer  , $data) ;

        return FreelancerResource::make($freelancer->load(['skills' , 'job_role']));
    }
}
