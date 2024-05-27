<?php

namespace App\Http\Requests\Freelancer;

use App\Constants\Gender;
use App\Rules\SyrianCityRule;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateFreelancerRequest extends FormRequest
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
        'profile_image' => ['image' , 'max:2000' , 'nullable'],
        'background_image' => ['image' , 'max:2000' , 'nullable'],
        'headline' => ['required' , 'string' , 'min:20' , 'max:200'],
        'description' => ['required' , 'string' , 'min:60' , 'max:4000'],
        'city' => ['required' , new SyrianCityRule()],
        'gender' => ['required' , Rule::in(Gender::$types)],
        'date_of_birth' => ['required' , 'date' , 'before_or_equal:' . Carbon::now()->subYears(16)->toDateString()],
        'job_role_id' => ['required' , 'exists:job_roles,id'],
        'skills' => ['required' , 'array' , 'min:5' , 'max:50'] ,
        'skills.*' => ['string' , 'exists:skills,name' , 'distinct'] ,
        ];
    }
}
