<?php

namespace App\Http\Requests\ClientOffer;

use Illuminate\Validation\Rule;
use App\Constants\ClientOfferStatus;
use Illuminate\Foundation\Http\FormRequest;

class FilterClientOfferForFreelancerRequest extends FormRequest
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
            'status' => ['string' , Rule::in([ClientOfferStatus::AVTIVE , ClientOfferStatus::CLOUSED]) ] ,
            'sub_category_id' => ['integer' , 'exists:sub_categories,id'] ,
            'skill_ids' => ['array' , 'max:25'] ,
            'skill_ids.*' => ['integer' , 'distinct' ,'exists:skills,id'] ,
            'min_days' => ['integer' , 'lt:max_days' ] ,
            'max_days' => ['integer' , 'gt:min_days'] ,
            'min_price' => ['integer' , 'lt:max_price'] ,
            'max_price' => ['integer' , 'gt:min_price'] ,
        ];
    }
}
