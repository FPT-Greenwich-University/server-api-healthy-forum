<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface IFavoriteRepository extends IEloquentRepository
{
    public function getListFavoritesDoctors(int $userId, int $perPage);
    public function getListFavoritesPosts(int $userId, int $perPage);

    /**
     * Get Detail favorite item
     *
     * @param integer $userId
     * @param integer $favoriteable_id
     */
    public function getDetailFavorite(int $userId, int $favoriteable_id);
    public function checkFavoriteExisted(int $userId, int $favoriteable_id, string $favoriteable_type);

    /**
     * Remove item from favorites list
     *
     * @param integer $userId
     * @param integer $favoriteable_id
     * @return void
     */
    public function removeFavorite(int $userId, int $favoriteable_id);
}
