<?php

namespace App\Http\Requests\JobOfferProposal;

use Illuminate\Validation\Rule;
use App\Models\JobOfferProposal;
use App\Constants\JobOfferStatus;
use Illuminate\Foundation\Http\FormRequest;

class CreateJobOfferProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        var_dump(auth()->user()) ;
        return [
            'job_offer_id' => [
                'required',

                Rule::exists('job_offers', 'id')
                ->where('status', JobOfferStatus::AVTIVE) ,

                Rule::unique('job_offer_proposals' , 'job_offer_id')
                    ->where('freelancer_id' , (string)auth()->user()?->role_id )  , 
                ] ,
            'message' => ['required' , 'string' , 'max:255'] ,
        ];
    }

    public function messages()
    {
        return [
            'job_offer_id.unique' => ['you can\'t propose for the same job twice '] , 
        ];
    }
}
