<?php

namespace App\Providers;

use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\PostLikeRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\ProfileRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\Common\IEloquentRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\ICommentRepository;
use App\Repositories\Interfaces\IPermissionRepository;
use App\Repositories\Interfaces\IPostLikeRepository;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\IProfileRepository;
use App\Repositories\Interfaces\IRoleRepository;
use App\Repositories\Interfaces\ITagRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IEloquentRepository::class, BaseRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IPostRepository::class, PostRepository::class);
        $this->app->bind(ITagRepository::class, TagRepository::class);
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IPostLikeRepository::class, PostLikeRepository::class);
        $this->app->bind(IRoleRepository::class, RoleRepository::class);
        $this->app->bind(IPermissionRepository::class, PermissionRepository::class);
        $this->app->bind(ICommentRepository::class, CommentRepository::class);
        $this->app->bind(IProfileRepository::class, ProfileRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
