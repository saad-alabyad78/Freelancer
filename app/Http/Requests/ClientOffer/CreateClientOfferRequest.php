<?php

namespace App\Http\Requests\ClientOffer;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateClientOfferRequest extends FormRequest
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
            'sub_category_id' => ['required' , 'integer' , 'exists:sub_categories,id'] ,
            'title' => ['required' , 'string' , 'min:1' , 'max:255' ],
            'description' => ['required' , 'string' , 'min:1' , 'max:2000'],
            'min_price' => ['required' , 'lt:max_price' ],
            'max_price' => ['required' , 'gt:min_price' ],
            'days' => ['required' , 'integer' , 'digits_between:1,3' , 'max:100'] ,

            'skill_ids' => ['required' , 'array' , 'min:5' , 'max:25'] ,
            'skill_ids.*' => ['required' , 'integer' , 'distinct' , 'exists:skills,id'] ,

            'file_ids' => ['array' , 'max:25'] ,
            'file_ids.*' => [
                'required' ,
                'integer' ,
                Rule::exists('files' , 'id')
                ->whereNull('filable_id')
                ->whereNull('filable_type') ] ,
        ];
    }
}
