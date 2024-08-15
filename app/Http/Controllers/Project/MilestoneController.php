<?php

namespace App\Http\Controllers\Project;

use App\Models\Client;
use App\Models\Project;
use App\Models\Milestone;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MilestoneResource;
use App\Http\Requests\StoreMilestoneRequest;
use App\Http\Requests\UpdateMilestoneRequest;
use App\Http\Resources\Project\ProjectResource;


class MilestoneController extends Controller
{
    /**
     * Get All Milestones
     * 
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Project $project)
    {
        $milstones = $project->milestones()->paginate(20) ;

        return MilestoneResource::collection($milstones) ;
    }
    
    /**
     * Store Milestone
     * @param \App\Models\Project $project
     * @param \App\Http\Requests\StoreMilestoneRequest $request
     * @return ProjectResource
     */
    public function store(Project $project , StoreMilestoneRequest $request)
    {
        $data = $request->validated() ;

        $project->milestones()->create($data) ;

        return ProjectResource::make($project->load(['milestones','files','client','freelancer'])) ;
    }
    /**
     * Update Milestone
     * @param \App\Models\Project $project
     * @param \App\Models\Milestone $milestone
     * @param \App\Http\Requests\UpdateMilestoneRequest $request
     * @return mixed|ProjectResource|\Illuminate\Http\JsonResponse
     */
    public function update(Project $project , Milestone $milestone , UpdateMilestoneRequest $request)
    {
        if($milestone->finished_at){
            return response()->json([
                'message' => 'the milestones already finished ' ,
            ]) ;
        }
    
        $milestone->update($request->validated()) ;

        if($milestone->client_ok and $milestone->freelancer_ok){
            $milestone->update(['finished_at' => now()->toDateTimeString()]) ;
            
            $freelancer = Freelancer::where('id' , $project->freelancer_id)->first() ;
            $client = Client::where('id' , $project->client_id)->first() ;

            //take it from the project money
            $freelancer->increment('money' , $milestone->price) ;
            $client->decrement('money' , $milestone->price) ;

            //todo add a bill
        }

        return ProjectResource::make($project->load(['milestones','files','client','freelancer'])) ;
    }
    /**
     * Delete Milestone
     * 
     * @param \App\Models\Project $project
     * @param \App\Models\Milestone $milestone
     * @return ProjectResource
     */
    public function delete(Project $project , Milestone $milestone)
    {
        $milestone->delete() ;

        return ProjectResource::make($project->load(['milestones','files','client','freelancer'])) ;
    }
}