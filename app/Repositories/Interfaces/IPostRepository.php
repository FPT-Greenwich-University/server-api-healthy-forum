<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface IPostRepository extends IEloquentRepository
{
    /**
     * @param int $per_page the number item in per page
     * @return mixed
     */
    public function getPosts(int $per_page);
    public function getPostsNotPublish(int $per_page);
    public function getDetailPost(int $id);
    public function getListPostIdByTag(int $tagId);
    public function getPostsByTag(int $tagId);
    public function getPostsByCategory(int $categoryId, int $perPage);
    public function updateStatusPost(int $postId, array $attributes);
}
