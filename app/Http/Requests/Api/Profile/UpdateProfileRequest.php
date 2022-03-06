<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateProfileRequest extends ApiFormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'regex:/((09|03|07|08|05)+([0-9]{8})\b)/'],
//            'firstName' => ['required'],
//            'lastName' => ['required'],
//            'description' => ['required'],
            'age' => ['required', 'regex:/^[1-9][0-9]*$/'],
            'gender' => ['required', 'regex:/^[0-1]?$/'],
            'city' => ['required', 'string'],
            'district' => ['required', 'string'],
            'ward' => ['required', 'string'],
            'street' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Phone is not invalid, format must be Viet Nam phone number',
            'age.regex' => 'Age not start at 0 and is a number',
            'gender.regex' => 'Invalid gender, please try again'
        ];
    }
}
