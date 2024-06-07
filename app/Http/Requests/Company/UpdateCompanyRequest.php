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

            'profile_image_id' => ['required_with:profile_image_url' , 'exists:images,id' , 'nullable'] ,
            'background_image_id' => ['required_with:background_image_id' , 'exists:images,id' , 'nullable'] ,

            'name' => ['min:3' , 'max:20' , 'string' , 'unique:companies,name'] ,
            'description' => ['string' , 'max:4000' ] ,
            'size' => ['string' , 'min:5' , 'max:20'] , 
            'city' => ['string' , new SyrianCityRule()] , 
            'region' => ['string' , 'min:3' , 'max:20'] ,
            'street_address' => ['string' , 'min:3' , 'max:30'] ,

            'contact_links' => ['array'] ,
            'contact_links.*' => ['string' , 'min:5' , 'distinct'] , 

            'company_phones' => ['array'] ,
            'company_phones.*' => ['regex:/^09[0-9]{8}$/' , 'distinct'] ,

            'gallery_images' => ['array'] ,
            'gallery_images.*.id' => ['required' , 'exists:images,id' , 'distinct'] , 
            'gallery_images.*.url' => ['required' ,'exists:images,url' , 'distinct'] , 
            
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
