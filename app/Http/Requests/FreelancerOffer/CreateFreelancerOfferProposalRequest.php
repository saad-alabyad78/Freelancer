<?php

namespace App\Http\Requests\FreelancerOffer;

use Illuminate\Validation\Rule;
use App\Constants\ClientOfferStatus;
use App\Constants\FreelancerOfferStatus;
use Illuminate\Foundation\Http\FormRequest;

class CreateFreelancerOfferProposalRequest extends FormRequest
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
            'freelancer_offer_id' => [
                'required' , 
                'integer' ,
                Rule::exists('freelancer_offers' , 'id')->where('status' , FreelancerOfferStatus::AVTIVE) ,
            ] ,
            'message' => ['required' , 'string' , 'min:10' , 'max:255'] ,
        ];
    }
}
