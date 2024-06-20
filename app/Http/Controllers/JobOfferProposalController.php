<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\Request;
use App\Models\JobOfferProposal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\JobOfferProposal\JobOfferProposalResource;
use App\Http\Requests\JobOfferProposal\CreateJobOfferProposalRequest;
use App\Http\Requests\JobOfferProposal\DeleteJobOfferProposalRequest;
use App\Http\Requests\JobOfferProposal\RejectJobOfferProposalRequest;
use App\Http\Requests\JobOfferProposal\UpdateJobOfferProposalRequest;

class JobOfferProposalController extends Controller
{
    public function filter()
    {
        //todo show list with filters (job offer id , date)
    }
    public function index()
    {
        //todo show list of them for freelancer order by date  
    }
    public function show(JobOfferProposal $proposal)
    {
        $this->authorize('view' , $proposal) ;

        return JobOfferProposalResource::make($proposal) ;
    }
    public function create(CreateJobOfferProposalRequest $request)
    {
        $this->authorize('create', JobOfferProposal::class);
        
        $data = $request->validated() ;
        $data['freelancer_id'] = (string)auth()->user()->role_id ;
        
        $proposal = JobOfferProposal::create($data) ;

        JobOffer::findOrFail($proposal->job_offer_id)->increment('proposals_count') ;
        
        return JobOfferProposalResource::make($proposal) ;
    }
    public function update(UpdateJobOfferProposalRequest $request)
    {
        $data = $request->validated() ;

        $proposal = JobOfferProposal::findOrFail($data['job_offer_proposal_id']) ;

        $proposal->update(['message' => $data['message']]) ;
        
        return JobOfferProposalResource::make($proposal) ;
    }
    public function delete(JobOfferProposal $jobOfferProposal)
    {
          
        $this->authorize('delete' , $jobOfferProposal) ;
        JobOffer::findOrFail($jobOfferProposal->job_offer_id)->decrement('proposals_count') ;
        $jobOfferProposal->delete() ;

        return response()->noContent() ;
    }
    public function reject(RejectJobOfferProposalRequest $request)
    {
        $proposalIds = $request->validated()['job_offer_proposal_ids'] ;

        JobOfferProposal::whereIn('id' , $proposalIds)
        ->update(['rejected_at' => now()->toDate()]) ;

        $jobOfferIds = JobOfferProposal::whereIn('id' ,$request->validated()['job_offer_proposal_ids'])
            ->pluck('job_offer_id')
            ->unique()
            ->toArray() ;

        JobOffer::whereIn('id' , $jobOfferIds)->decrement('proposals_count') ;

        return response()->noContent() ;
    }
    public function accept(JobOfferProposal $jobOfferProposal)
    {
        $this->authorize('accept' , $jobOfferProposal) ;

        $jobOfferProposal->update(['accepted' => now()->toDate()]) ;
        JobOffer::where('id' , $jobOfferProposal->job_offer_id)->decrement('proposals_count') ;
        
        //todo : send notification to freelancer (firebase) ;

        //allows him to access the chat with company
        //allows the company to acces the chat with him
    }

}
