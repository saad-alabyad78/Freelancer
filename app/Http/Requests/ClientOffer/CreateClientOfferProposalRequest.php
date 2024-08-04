<?php

namespace App\Http\Requests\ClientOffer;

use Illuminate\Validation\Rule;
use App\Constants\ClientOfferStatus;
use Illuminate\Foundation\Http\FormRequest;

class CreateClientOfferProposalRequest extends FormRequest
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
            'client_offer_id' => [
                'required' , 
                'integer' ,
                Rule::exists('client_offers' , 'id')->where('status' , ClientOfferStatus::ACTIVE) ,
            ] ,
            'message' => ['required' , 'string' , 'min:10' , 'max:255'] ,
            'days' => ['integer'] ,
            'price' => ['integer']
        ];
    }
}
