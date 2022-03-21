<?php

namespace App\Http\Requests\Api\Post\Comment;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateChildPostCommentRequest extends  ApiFormRequest
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
//        TODO: min va max content
        return [
            'content' => ['required'],
            'parent_comment_id' => ['required']
        ];
    }
}
