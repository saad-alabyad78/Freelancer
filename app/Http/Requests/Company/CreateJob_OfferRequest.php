<?php

namespace App\Http\Requests\Company;

use App\Constants\Gender;
use App\Rules\GenderRule;
use App\Constants\LocationType;
use Illuminate\Validation\Rule;
use App\Constants\AttendenceType;
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
            'industry_name' => ['required' , 'exists:industries,name' , 'string'] ,
            'job_role_id' => ['required' , 'exists:job_roles,id'] ,
            
            'location_type' => ['required', 'string', Rule::in(LocationType::$types)],
            'attendence_type' => ['required', 'string', Rule::in(AttendenceType::$types)],

            'max_salary' => ['integer', 'min:0', 'max:100000000', 'gte:min_salary' , new FieldsTogetherOrNoneRule('min_salary')],
            'min_salary' => ['integer', 'min:0', 'max:100000000', 'lte:max_salary' , new FieldsTogetherOrNoneRule('max_salary')],


            'max_age' => ['integer', 'min:18', 'max:60', 'gte:min_salary' , new FieldsTogetherOrNoneRule('min_age')],
            'min_age' => ['integer', 'min:18', 'max:60', 'lte:max_salary' , new FieldsTogetherOrNoneRule('max_age')],
            
            'description' => ['required' ,'string' ,  'min:40' ,' max:40000' ] ,

            'transportation' => ['required' , 'bool'] ,
            'health_insurance' => ['required' , 'bool'] ,
            'military_service' => ['required' , 'bool'] ,
            'gender' => ['nullable' , Rule::in(Gender::$types)] ,
            
            'skills' => ['required' , 'array' , 'min:5' , 'max:25'] ,
            'skills.*' => ['string' ,'exists:skills,name' , 'distinct'] ,
        ];
    }
}
