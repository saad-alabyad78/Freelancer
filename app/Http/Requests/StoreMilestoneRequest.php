<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMilestoneRequest extends FormRequest
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
        $project = $this->route('project') ;
        return [
            'price' => ['required' , 'integer' , 'max:'.$project?->client_money],
            'deadline' => ['required' , 'date' , function($attribute , $value , $fail){
                if($value < now()->toDateString()){
                    $fail('deadline must be in the future') ;
                }
            }],
            'description' => ['required' , 'string'],
        ];
    }
}
