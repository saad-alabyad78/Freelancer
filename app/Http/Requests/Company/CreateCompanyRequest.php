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

            'profile_image_url' => ['required_with:profile_image_id' ,'string' , 'nullable'] ,
            'profile_image_id' => ['required_with:profile_image_url' , 'exists:images,id' , 'nullable'] ,
            'background_image_url' => ['required_with:background_image_id' ,'strign' , 'nullable'] ,
            'background_image_id' => ['required_with:background_image_id' , 'exists:images,id' , 'nullable'] ,

            'name' => ['required' , 'min:3' , 'max:20' , 'string' , 'unique:companies,name'] ,
            'description' => ['required' , 'string' , 'max:4000' ] ,
            'size' => ['required' , 'string'] , 
            'city' => ['required' , 'string' , new SyrianCityRule()] , 
            'region' => ['required' , 'string' , 'min:3' , 'max:20'] ,
            'street_address' => ['required' , 'string' , 'min:3' , 'max:30'] ,

            'gallery_image_ids' => ['array' , 'max:25'] ,
            'gallery_images_ids.*' => ['exists:images,id' , 'max:2000' , 'distinct'] , 

            'contact_links' => ['array'] ,
            'contact_links.*' => ['string' , 'min:5' , 'distinct'] , 

            'company_phones' => ['array'] ,
            'company_phones.*' => ['string' , 'regex:/^09[0-9]{8}$/' , 'distinct'] ,
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
