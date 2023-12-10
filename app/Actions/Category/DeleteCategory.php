<?php

namespace App\Actions\Category;

use App\Models\Category;

class DeleteCategory
{
    public function execute(Category $category): Category
    {
        $category->delete();

        return tap($category)->refresh();
    }
}
