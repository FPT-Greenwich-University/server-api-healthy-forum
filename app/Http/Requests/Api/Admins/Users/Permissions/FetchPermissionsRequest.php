<?php

namespace App\Http\Requests\Api\Admins\Users\Permissions;

use App\Http\Requests\Api\ApiFormRequest;

class FetchPermissionsRequest extends ApiFormRequest
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
            'role_id' => ['required', 'array'],
            'role_id.*' => ['numeric'],
        ];
    }
}
