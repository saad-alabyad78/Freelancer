<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationsRequest extends FormRequest
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
            // 'title' => ['required' , 'string' , 'max:255'],
            // 'description' => ['required' , 'string' , 'max:40000'],
            // 'type' => ['string'],
            // 'state' => ['integer'],
            // 'model_id' => ['nullable' , 'integer'],
            // 'model_type' => ['nullable' , 'string'],
        ];
    }
}
