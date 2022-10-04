<?php

namespace App\Services\PostServices;

use Illuminate\Http\Request;

interface PostServiceInterface
{
    public function createNewPost(string $filePath, Request $request);

    public function updatePost(int $postId, Request $request);

    public function updatePostView(int $postId);

    public function deletePost(int $userId, int $postId, Request $request);
}
