<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => ['string' , 'max:255'] ,
            'description' => ['string'] ,
            'price' => ['integer'] ,
            
            'image_id' => ['integer' , 'exists:images,id'] ,

            'image_ids' => ['array'] ,
            'image_ids.*' => ['integer' , 'exists:images,id' , 'max:2048'] ,

            'file_ids' => ['array'] ,
            'file_ids.*' => ['integer' , 'exists:files,id'] ,
        ];
    }
}
