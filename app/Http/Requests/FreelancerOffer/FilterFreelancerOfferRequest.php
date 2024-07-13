<?php

namespace App\Http\Requests\FreelancerOffer;

use App\Models\ClientOffer;
use Illuminate\Validation\Rule;
use App\Constants\ClientOfferStatus;
use Illuminate\Foundation\Http\FormRequest;

class FilterFreelancerOfferRequest extends FormRequest
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
            'status' => ['string' , Rule::in(ClientOfferStatus::$types)] ,
        ];
    }
}
