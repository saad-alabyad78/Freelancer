<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\JobOfferProposal;
use App\Http\Resources\JobOfferProposal\JobOfferProposalResource;
use App\Http\Requests\JobOfferProposal\CreateJobOfferProposalRequest;
use App\Http\Requests\JobOfferProposal\FilterJobOfferProposalRequest;
use App\Http\Requests\JobOfferProposal\RejectJobOfferProposalRequest;
use App\Http\Requests\JobOfferProposal\UpdateJobOfferProposalRequest;
/**
 *@group JobOffer-Proposal Managment
 *
 **/
class JobOfferProposalController extends Controller
{
    /**
     * Filter (company) 
     *
     * filter job offer proposal based on job offer id and sort them by created date.
     * 
     * @param  FilterJobOfferProposalRequest  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function filter(FilterJobOfferProposalRequest $request)
    {
        $this->authorize('filter', JobOfferProposal::class);

        $data = $request->validated();

        $company = Company::findOrFail(auth()->user()->role_id);

        $proposals = $company->job_offer_proposals()
                            ->when($data['job_offer_id'] ?? false , function($query) use ($data){
                                return $query->where('job_offer_id' , $data['job_offer_id']) ;
                            })
                            ->orderBy('created_at', $data['order'] ?? 'asc')
                            ->paginate(20);

        return JobOfferProposalResource::collection($proposals);
    }
    /**
     * Display a list(freelancer)
     * 
     * display a listing of proposals .
     * for the freelancer
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $user = auth()->user();

        $this->authorize('index', JobOfferProposal::class);


        $proposals = JobOfferProposal::where('freelancer_id', $user->role_id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
                        
        return JobOfferProposalResource::collection($proposals);
    }
    /**
     * Display specified proposal (company|freelancer).
     * 
     * company or freelancer can see the proposal
     * 
     * @param  JobOfferProposal  $proposal
     * @return JobOfferProposalResource
     */
    public function show(JobOfferProposal $jobOfferProposal)
    {
        $this->authorize('view' , $jobOfferProposal) ;

        return JobOfferProposalResource::make($jobOfferProposal) ;
    }
    /**
     * Propose | Create (freelancer)
     * 
     * Store a newly created job offer proposal.
     *
     * @param  CreateJobOfferProposalRequest  $request
     * @return JobOfferProposalResource
     */
    public function create(CreateJobOfferProposalRequest $request)
    {
        $this->authorize('create', JobOfferProposal::class);

        $data = $request->validated() ;
        $data['freelancer_id'] = (string)auth()->user()->role_id ;

        $proposal = JobOfferProposal::create($data) ;

        JobOffer::findOrFail($proposal->job_offer_id)->increment('proposals_count') ;

        return JobOfferProposalResource::make($proposal) ;
    }
    /**
     * Update (freelancer).
     *
     * freelancer update the proposal message
     * but that does not updates the date of it 
     * 
     * @param  UpdateJobOfferProposalRequest  $request
     * @return JobOfferProposalResource
     */
    public function update(UpdateJobOfferProposalRequest $request)
    {
        $data = $request->validated() ;

        $proposal = JobOfferProposal::findOrFail($data['job_offer_proposal_id']) ;

        $proposal->update(['message' => $data['message']]) ;

        return JobOfferProposalResource::make($proposal) ;
    }
    /**
     * Delete | Cancel (freelancer).
     *
     * freelancer delete the proposal
     * 
     * @param  JobOfferProposal  $jobOfferProposal
     * @return \Illuminate\Http\Response
     */
    public function delete(JobOfferProposal $jobOfferProposal)
    {
        $this->authorize('delete' , $jobOfferProposal) ;

        JobOffer::findOrFail($jobOfferProposal->job_offer_id)->decrement('proposals_count') ;

        $jobOfferProposal->delete() ;

        return response()->noContent() ;
    }
    /**
     * Reject (company)
     * 
     * reject one or more job offer proposals.
     *
     * company reject one or more proposals
     * 
     * @param  RejectJobOfferProposalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function reject(RejectJobOfferProposalRequest $request)
    {
        $proposalIds = $request->validated()['job_offer_proposal_ids'];

        JobOfferProposal::whereIn('id', $proposalIds)
            ->update(['rejected_at' => now()->toDateTimeString()]);

        $jobOfferIds = JobOfferProposal::whereIn('id', $proposalIds)
            ->pluck('job_offer_id')
            ->unique()
            ->toArray();

        JobOffer::whereIn('id', $jobOfferIds)->decrement('proposals_count');

        return response()->noContent();
    }

    /**
     * Accept (company)
     * 
     * accept a job offer proposal.
     *
     *company accepts the proposal
     * 
     * @param  JobOfferProposal  $jobOfferProposal
     * @return JobOfferProposalResource
     */
    public function accept(JobOfferProposal $jobOfferProposal)
    {
        $this->authorize('accept', $jobOfferProposal);

        $jobOfferProposal->update(['accepted_at' => now()->toDateTimeString()]);
        JobOffer::where('id', $jobOfferProposal->job_offer_id)->decrement('proposals_count');

        // TODO: send notification to freelancer (firebase)
        // Allows the freelancer and the company to access the chat with each other

        return JobOfferProposalResource::make($jobOfferProposal);
    }


}
