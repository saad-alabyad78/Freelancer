<?php

namespace App\Http\Requests\JobOfferProposal;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobOfferProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job_offer_proposal_id' => [
                'required' ,
                Rule::exists('job_offer_proposals' , 'id')
                    ->whereNull('rejected_at')
                    ->whereNull('accepted_at') ,
                ] ,
            'message' => ['string' , 'required' , 'max:255'] ,
        ];
    }
}
