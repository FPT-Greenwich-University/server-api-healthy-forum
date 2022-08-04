<?php

namespace App\Http\Requests\Api\Mails;

use App\Http\Requests\Api\ApiFormRequest;

class SendEmailContractRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'min:3'],
            'from_email' => ['required', 'email'],
            'from_user_name' => ['required', 'string'],
            'body' => ['required', 'string', 'min:10'],
            'receiver_email' => ['required', 'email']
        ];
    }
}
