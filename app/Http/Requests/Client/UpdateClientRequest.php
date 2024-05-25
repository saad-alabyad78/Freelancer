<?php

namespace App\Http\Requests\Client;

use App\Constants\Gender;
use App\Rules\SyrianCityRule;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'date_of_birth' => [ 'date' , 'before_or_equal:' . Carbon::now()->subYears(16)->toDateString() ] ,
            'city' => [ new SyrianCityRule() ] ,
            'gender' => [ Rule::in(Gender::$types) ] ,
        ];
    }
}
