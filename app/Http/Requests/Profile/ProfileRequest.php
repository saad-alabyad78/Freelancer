<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $required = request()->isMethod('post') ? 'required' : '' ; 
  
        return [
            'gender' => [$required , 'string' , 'in:male,female'] ,
            'avatar_image' => ['image' , 'min:10' , 'max:2048'] ,
            'cover_image' => ['image' , 'min:10' , 'max:2048'] ,
        ];      
    }
}
