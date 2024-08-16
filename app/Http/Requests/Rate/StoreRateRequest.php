<?php

namespace App\Http\Requests\Rate;

use Illuminate\Validation\Rule;
use App\Constants\RatingConstants;
use Illuminate\Foundation\Http\FormRequest;

class StoreRateRequest extends FormRequest
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
            'model_id' => [ 'required' , 'integer'] ,
            'model_type' => [ 
                'required' ,
                'string' ,
                Rule::in(array_keys(RatingConstants::MAP_TO_MODEL))
            ] ,
            'description' => [ 'required' , 'string'] ,
            'number' => [ 'required' , 'string'] ,
        ];
    }
}
