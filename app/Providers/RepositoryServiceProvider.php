<?php

namespace App\Providers;

use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\ChatRoomRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\FileManagerRepository;
use App\Repositories\Eloquent\MessageRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\PostLikeRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\PostTagRepository;
use App\Repositories\Eloquent\ProfileRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\Common\IEloquentRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IChatRoomRepository;
use App\Repositories\Interfaces\ICommentRepository;
use App\Repositories\Interfaces\IFavoriteRepository;
use App\Repositories\Interfaces\IFileManagerRepository;
use App\Repositories\Interfaces\IMessageRepository;
use App\Repositories\Interfaces\IPermissionRepository;
use App\Repositories\Interfaces\IPostLikeRepository;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\IPostTagRepository;
use App\Repositories\Interfaces\IProfileRepository;
use App\Repositories\Interfaces\IRoleRepository;
use App\Repositories\Interfaces\ITagRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\Eloquent\FavoriteRepository;
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
        $this->app->bind(IPostTagRepository::class, PostTagRepository::class);
        $this->app->bind(IFavoriteRepository::class, FavoriteRepository::class);
        $this->app->bind(IMessageRepository::class, MessageRepository::class);
        $this->app->bind(IChatRoomRepository::class, ChatRoomRepository::class);
        $this->app->bind(IFileManagerRepository::class, FileManagerRepository::class);
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
