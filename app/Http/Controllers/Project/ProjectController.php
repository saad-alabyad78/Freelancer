<?php

namespace App\Http\Controllers\Project;

use App\Models\File;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Freelancer;
use App\Models\ClientOffer;
use Illuminate\Http\Request;
use App\Constants\ClientOfferStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\BillResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Requests\Project\UploadFilesRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ClientOffer\ClientOfferResource;
/**
 *@group Client Offer Milestones 
 */
class ProjectController extends Controller
{
    /**
     * All My Client Offer (freelancer || Client\)
     * 
     * add query param ?type=
     * to filter project according on the type
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $user = User::where('id' , auth('sanctum')->id())->first() ;

        if($user->role_type == Freelancer::class)
        {
            $offers = ClientOffer::with(['client' , 'freelancer'])
            ->where('freelancer_id' , $user->role_id);
        }
        if($user->role_type == Client::class)
        {
            
            $offers = ClientOffer::with(['client' , 'freelancer'])
            ->where('client_id' , $user->role_id);
        }

        if(request()->has('type')){
            $offers->where('type' , request()->query('type' , ClientOfferStatus::IN_PROGRESS)) ;
        }

        return ClientOfferResource::collection($offers->paginate(10)) ;
    }
    /**
     * Client OK
     * @param \App\Models\Project $project
     * @return void
     */
    public function clientOk(Project $project)
    {
        $project->update(['client_ok' => true]) ;
    }
    /**
     * Freelancer OK
     * @param \App\Models\Project $project
     * @return void
     */
    public function freelancerOk(Project $project)
    {
        $project->update(['freelancer_ok' => true]) ;
    }
    /**
     * Update Project Files
     * @param \App\Models\Project $project
     * @param \App\Http\Requests\Project\UpdateProjectRequest $request
     * @return ProjectResource
     */
    public function update(Project $project , UpdateProjectRequest $request)
    {
        if($request->has('file_ids')){
            File::whereIn('id' , $request->input('file_ids' , []))
            ->update([
                'filable_id' => $project->id , 
                'filable_type' => Project::class ,
            ]) ;
        }

        return ProjectResource::make($project) ;
    }
    
    public function reportToAdmin(Project $project)
    {
        //todo
    }

    /**
     * Delete or Cancel Project
     * 
     * @param \App\Models\Project $project
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(Project $project)
    {
        $offer = ClientOffer::where('id' , $project->client_offer_id)
        ->update([
            'freelancer_id' => null , 
            'status' => ClientOfferStatus::ACTIVE ,
            'posted_at' => now()->toDateTimeString() ,
        ]) ;
        
        $project->delete() ;
        return ClientOfferResource::make($offer) ;
    }
}
