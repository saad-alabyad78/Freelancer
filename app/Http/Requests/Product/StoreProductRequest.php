<?php

namespace App\Http\Requests\Product;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['required' , 'string' , 'max:255'] ,
            'description' => ['required' , 'string'] ,
            'price' => ['required' , 'integer'] ,

            'image_id' => ['required' , 'integer' , Rule::exists('images')
            ->whereNull('imagable_id')] ,

            'image_ids' => ['required' , 'array'] ,
            'image_ids.*' => [
                'required' ,
                'integer' ,
                Rule::exists('images')
                ->whereNull('imagable_id') ,
                'max:2048'
            ] ,

            'file_ids' => ['required' , 'array'] ,
            'file_ids.*' => [
                'required' ,
                'integer' ,
                Rule::exists('files')
                ->whereNull('filable_id')] ,
        ];
    }
}
