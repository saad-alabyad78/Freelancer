<?php

namespace App\Http\Requests\JobOfferProposal;

use App\Models\Company;
use Illuminate\Validation\Rule;
use App\Models\JobOfferProposal;
use Illuminate\Foundation\Http\FormRequest;

class RejectJobOfferProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() and auth()->user()->role_type == Company::class ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job_offer_proposal_ids' => ['array' , 'required'] ,
            'job_offer_proposal_ids.*' => [
                'required' ,
                'distinct' ,
                function($attribute ,$value , $fail){

                    $proposal = JobOfferProposal::where('id', $value) 
                    ->whereNull('rejected_at')->whereNull('accepted_at')->first() ;

                    if(!$proposal){
                        $fail('there is no such job offer proposal with id ' . $value . ' or it could be already rejected or accepted') ;
                        return ;
                    }
                    if($proposal->job_offer()->first()->company_id != auth()->user()->role_id){
                        $fail('this is not your job offer proposal ') ;
                    }

                }
                ]
        ];
    }
}
