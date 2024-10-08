<?php

namespace App\Http\Requests\Company;

use App\Rules\SyrianCityRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'lon' => ['string'] ,
            'lat' => ['string'] ,

            'profile_image_id' => ['nullable' , 'exists:images,id'] ,
            'background_image_id' => ['nullable' , 'exists:images,id'] ,

            'name' => ['min:3' , 'max:20' , 'string' , 'unique:companies,name,'.auth('sanctum')->user()?->role['id']] ,
            'description' => ['string' , 'max:4000' ] ,
            'size' => ['string' , 'min:1' , 'max:255'] , 
            'city' => ['string' , new SyrianCityRule()] , 
            'region' => ['string' , 'min:3' , 'max:20'] ,
            'street_address' => ['string' , 'min:3' , 'max:30'] ,

            'gallery_image_ids' => ['array'] ,
            'gallery_image_ids.*.id' => ['required' , 'exists:images,id' , 'distinct'] ,  
        ];
    }

    public function messages():array
    {
        return [
            'company_phones.*.regex' => 'company phone must be a valid syrian phone 09 then 8 digits' ,
        ];
    } 
    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'name must be unique company name '
            ] ,
        ] ;
    }
}
