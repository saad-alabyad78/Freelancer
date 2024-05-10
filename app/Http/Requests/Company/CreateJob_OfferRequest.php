<?php

namespace App\Http\Requests\Company;

use App\Rules\GenderRule;
use App\Rules\Job_OfferTypesRule;
use App\Rules\Job_OfferStatusRule;
use App\Rules\FieldsTogetherOrNoneRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateJob_OfferRequest extends FormRequest
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
            'type' => ['required', 'string', new Job_OfferTypesRule()],

            'max_salary' => ['integer', 'min:0', 'max:100000000', 'gte:min_salary' , new FieldsTogetherOrNoneRule('min_salary')],
            'min_salary' => ['integer', 'min:0', 'max:100000000', 'ste:max_salary' , new FieldsTogetherOrNoneRule('max_salary')],


            'max_age' => ['integer', 'min:18', 'max:60', 'gte:min_salary' , new FieldsTogetherOrNoneRule('min_age')],
            'min_age' => ['integer', 'min:18', 'max:60', 'ste:max_salary' , new FieldsTogetherOrNoneRule('max_age')],
            
            'description' => ['string' , 'required' , 'min:100' ] ,

            'transportation' => ['required' , 'bool'] ,
            'health_insurance' => ['required' , 'bool'] ,
            'military_service' => ['required' , 'bool'] ,
            'gender' => ['string' , new GenderRule()] ,
            'decription' => ['required' , 'min:100' , 'max:40000'] ,

            'job_role' => ['required' , 'exists:job_roles,name'] ,
            
            'skills' => ['required' , 'array' , 'min:5' , 'max:25'] ,
            'skills.*' => ['required' , 'string' ,'exists:skills,name'] ,
        ];
    }
}
