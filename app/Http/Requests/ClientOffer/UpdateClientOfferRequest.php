<?php

namespace App\Http\Requests\ClientOffer;

use App\Models\File;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientOfferRequest extends FormRequest
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
            'client_offer_id' => ['required' , 'integer' , 'exists:client_offers,id'] ,
            'sub_category_id' => ['integer' , 'exists:sub_categories,id'] ,
            'title' => ['string' , 'min:5' , 'max:255' ], 
            'description' => [ 'string' , 'min:10' , 'max:2000'],
            'min_price' => [ 'lt:max_price' ],
            'max_price' => [ 'gt:min_price' ],
            'days' => [ 'integer' , 'digits_between:1,3' , 'max:100'] ,

            'skill_ids' => ['required' , 'array' , 'min:5' , 'max:25'] ,
            'skill_ids.*' => [ 'integer' , 'distinct' , 'exists:skills,id'] ,

            'file_ids' => ['present' , 'array' , 'min:0' , 'max:25'] ,
            'file_ids.*' => [
                'integer' ,
                function($attribute, $value, $fail){
                    $exists = File::where('id', $value)
                    ->where(function ($query) {
                        $query->whereNull('filable_id')
                              ->whereNull('filable_type');
                    })
                    ->orWhere(function ($query) {
                        $query->where('filable_id' , request('client_offer_id'))
                              ->where('filable_type' , File::class);
                    })
                    ->exists();

                    if (!$exists) {
                        $fail("The $attribute file is for others .");
                    }
                } ,
            ]
        ];
    }
}
