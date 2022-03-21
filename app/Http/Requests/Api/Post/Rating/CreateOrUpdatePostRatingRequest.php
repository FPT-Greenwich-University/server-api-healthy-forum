<?php

namespace App\Http\Requests\Api\Post\Rating;

use App\Http\Requests\Api\ApiFormRequest;

class CreateOrUpdatePostRatingRequest extends ApiFormRequest
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
            'point' => ['required', 'regex:/^[1-5]{1}$/']
        ];
    }

    public function messages()
    {
        return [
            'point.regex' => 'The rating point must be a number and value must be from 1 to 5'
        ];
    }
}
