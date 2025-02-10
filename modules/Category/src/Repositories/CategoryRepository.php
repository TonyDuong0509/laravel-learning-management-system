<?php

namespace Modules\Category\src\Repositories;

use App\Repositories\BaseRepository;
use Modules\Category\src\Models\Category;
use Modules\Category\src\Repositories\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function getModel()
    {
        return Category::class;
    }

    public function getCategories()
    {
        return $this->model->with('subCategories')
            ->where('parent_id', 0)
            ->select(['id', 'name', 'slug', 'parent_id', 'created_at'])
            ->latest();
    }

    public function getAllCategories()
    {
        return $this->getAll();
    }
}
