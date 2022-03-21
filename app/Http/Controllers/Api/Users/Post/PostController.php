<?php

namespace App\Http\Controllers\Api\Users\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Check the post exist
     * @param $postID
     * @return bool --true if the post exist, otherwise false
     */
    public static function checkPostExist($postID): bool
    {
        $post = Post::find($postID);
        if (is_null($post)) {
            return false;
        }
        return true;
    }
}
