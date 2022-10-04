<?php

namespace App\Http\Requests\Api\Chats;

use App\Http\Requests\Api\ApiFormRequest;

class CreateChatRoomRequest extends ApiFormRequest
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
            'targetUserId' => ['required', 'numeric']
        ];
    }
}
