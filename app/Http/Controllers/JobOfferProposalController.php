<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\JobOfferProposal\CreateJobOfferProposalRequest;

class JobOfferProposalController extends Controller
{

    public function filter()
    {
        //todo filter them for company
    }
    public function index()
    {
        //todo show list of them 
    }
    public function show()
    {
        //todo show on with details
    }
    public function create(CreateJobOfferProposalRequest $request)
    {
        return $data = $request->validated() ;
        
        //todo vallidate that freelancer can send proposal to this joboffer
        //todo freelancer send proposal
    }
    public function update()
    {
        //todo freelancer can update the proposal before its rejected or accepted
    }
    public function delete()
    {
        //todo freelancer delete proposal
    }
    public function reject()
    {
        //todo company reject proposal (one or chunk)
    }
    public function accept()
    {
        //todo company accepts proposal

        //send notification to freelancer (firebase) ;

        //allows him to access the chat with company
        //allows the company to acces the chat with him
    }

}
