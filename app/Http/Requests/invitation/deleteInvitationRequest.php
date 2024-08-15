<?php

namespace App\Http\Requests\invitation;

use Illuminate\Foundation\Http\FormRequest;

class deleteInvitationRequest extends FormRequest
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
            'invitation_ids' => ['required' , 'array'] ,
            'invitation_ids.*' => ['required' , 'integer' , 'distinct' , 'exists:invitations,id'] ,
        ];
    }
}
