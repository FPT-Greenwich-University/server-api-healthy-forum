<?php

namespace App\Repositories\Eloquent;

use App\Models\Favorite;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IFavoriteRepository;
use Exception;

class FavoriteRepository extends BaseRepository implements IFavoriteRepository
{
    public function __construct(Favorite $model)
    {
        parent::__construct($model);
    }

    public function getListFavoritesDoctor(int $userId, int $perPage)
    {
        try {
            return $this->model::where('favorites.user_id', $userId)
                ->where('favoriteable_type', 'App\Models\User')
                ->join('users', 'favorites.favoriteable_id', 'users.id')
                ->orderBy('favorites.id', 'desc')
                ->select('users.id', 'users.name', 'users.email', 'image_url')
                ->paginate($perPage);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function checkFavoriteExisted(int $userId, int $favoriteable_id, string $favoriteable_type)
    {
        try {
            return $this->model::where('user_id', '=', $userId)
                ->where('favoriteable_id', '=', $favoriteable_id)
                ->where('favoriteable_type', '=', $favoriteable_type)
                ->first();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function removeFavorite(int $userId, int $favoriteable_id)
    {
        try {
            $favorite =  $this->getDetailFavorite($userId, $favoriteable_id);
            return $favorite->delete();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getDetailFavorite(int $userId, int $favoriteable_id)
    {
        try {
            return $this->model::where("user_id", "=", $userId)
                ->where("favoriteable_id", "=", $favoriteable_id)
                ->first();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
