<?php

namespace App\Http\Requests\ClientOffer;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FilterClientOfferForFreelancerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [ 'string' , Rule::in(ClientOfferStatus::$types) ] ,
            'sub_category_id' => ['integer' , 'exists:sub_categories,id'] ,
            'skill_ids' => ['array' , 'max:25'] ,
            'skill_ids.*' => ['integer' , 'exists:skills,id'] ,
        ];
    }
}
