<?php

namespace App\Http\Requests\Freelancer;

use Carbon\Carbon;
use App\Constants\Gender;
use App\Rules\SyrianCityRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFreelancerRequest extends FormRequest
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
                'headline' => [ 'string' , 'min:20' , 'max:200'],
                'description' => [ 'string' , 'min:60' , 'max:4000'],
                'city' => [ new SyrianCityRule()],
                'gender' => [ Rule::in(Gender::$types)],
                'date_of_birth' => [ 'date' , 'before_or_equal:' . Carbon::now()->subYears(16)->toDateString()],
                'job_role_id' => [ 'exists:job_roles,id'],
                'skills' => ['array' , 'min:5' , 'max:50'] ,
                'skills.*' => ['string' , 'exists:skills,name' , 'distinct'] ,
                
        ];
    }
}
