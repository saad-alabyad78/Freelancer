<?php

namespace App\Http\Requests\FreelancerOffer;

use Illuminate\Validation\Rule;
use App\Constants\ClientOfferStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFreelancerOfferProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'freelancer_offer_proposal_id' => [
                'required' , 
                'integer' ,
                Rule::exists('freelancer_offer_proposals' , 'id')
                ->where('client_id' , auth('sanctum')->user()->role_id) ,
            ] ,
            'message' => ['required' , 'string' , 'min:10' , 'max:255'] ,
        ];
    }
}
