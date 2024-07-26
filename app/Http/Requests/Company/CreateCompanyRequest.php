<?php

namespace App\Http\Requests\Company;

use App\Rules\SyrianCityRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
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
            'industry_name' => ['string' , 'required' , 'exists:industries,name'] ,

            'profile_image_id' => ['nullable' , 'exists:images,id' ] ,
            'background_image_id' => ['nullable' , 'exists:images,id' ] ,

            'name' => ['required' , 'min:3' , 'max:20' , 'string' , 'unique:companies,name'] ,
            'description' => ['required' , 'string' , 'max:4000' ] ,
            'size' => ['required' , 'string'] , 
            'city' => ['required' , 'string' , new SyrianCityRule()] , 
            'region' => ['required' , 'string' , 'min:3' , 'max:20'] ,
            'street_address' => ['required' , 'string' , 'min:3' , 'max:30'] ,

            'gallery_image_ids' => ['array' , 'max:25'] ,
            'gallery_images_ids.*' => ['exists:images,id' , 'max:2048' , 'distinct'] ,
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
