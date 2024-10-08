<?php

namespace App\Http\Requests\invitation;

use App\Models\Invitation;
use Illuminate\Foundation\Http\FormRequest;

class SendInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('sendInvitation', Invitation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'freelancer_id' => [
            'required',
            'exists:users,id',
            function ($attribute, $value, $fail) {
                $user = \App\Models\User::find($value);
                if (!$user || $user->role_name !== 'freelancer') {
                    $fail('The selected user is not a freelancer.');
                }
            },
        ],
    ];
}
}
