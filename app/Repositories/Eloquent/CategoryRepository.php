<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Post;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IPostRepository;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{
    private IPostRepository $postRepos;
    public function __construct(Category $model, IPostRepository $postRepository)
    {
        parent::__construct($model);
        $this->postRepos = $postRepository;
    }

    /**
     * @param int $id category's id
     * @return bool true if success
     * otherwise false
     */
    public function handleDeleteCategory(int $id): bool
    {
        parent::findById($id);
        $posts = $this->postRepos->getPostsByCategory($id);

        if ($posts->total() === 0) { // if the category not used by the post then accept delete
            parent::delete($id);

            return true;
        }

        return false;
    }

}
