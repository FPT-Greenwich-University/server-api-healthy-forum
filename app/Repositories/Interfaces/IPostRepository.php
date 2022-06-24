<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface IPostRepository extends IEloquentRepository
{
    public function getPosts(int $per_page);

    public function getPostsNotPublish(int $per_page);

    public function getDetailPost(int $id);

    public function getListPostIdByTag(int $tagId);

    public function getPostsByTag(int $tagId, int $perPage);

    public function getPostsByCategory(int $categoryId, int $perPage);

    public function updateStatusPost(int $postId, array $attributes);

    public function searchPosts(string $title, int $perPage);

    public function assignPostTags(int $postId, array $tags);

    public function getPostsByUser(int $userId);

    public function getDetailPostByUser(int $userId, int $postId);

    public function updatePostTags(int $postId, array $tags);

    public function updatePost($postId, array $attributes);

    public function createPostImage(int $postId, string $filePath);
    public function updatePostImage(int $postId, string $filePath);
    /**
     * Delete post with all related relationship constraint
     */
    public function deletePost(int $postId);
    public function storePostComment(int $postId, array $attributes);
    public function addFavoritePost(int $postId, int $userId);
}
