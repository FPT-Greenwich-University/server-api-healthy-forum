<?php

namespace App\Http\Requests\Api\Post;

use App\Http\Requests\Api\ApiFormRequest;

class UpdatePostRequest extends ApiFormRequest
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
            'title' => ['required', 'min:10', 'max:100'],
            'description' => ['required', 'min:20'],
            'category_id' => ['required', 'numeric'],
            'body' => ['required'],
            'thumbnail' => ['image'],
            'tags' => ['required', 'array', 'min:1'], // array tag id
            'tags.*' => ['required', 'numeric']// tag id
        ];
    }
}
