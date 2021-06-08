<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoriesController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getCategories(Request $request): AnonymousResourceCollection
    {
        $categories = Category::query()->paginate(10);

        return CategoryResource::collection($categories);
    }

    /**
     * @param Category $category
     * @return CategoryResource
     */
    public function getCategory(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * @param CreateCategoryRequest $request
     * @return CategoryResource
     */
    public function createCategory(CreateCategoryRequest $request): CategoryResource
    {
        $category = new Category($request->validated());
        $category->save();

        return new CategoryResource($category);
    }

    /**
     * @param Category $category
     * @param EditCategoryRequest $request
     * @return CategoryResource
     */
    public function editCategory(Category $category, EditCategoryRequest $request): CategoryResource
    {
        $category->name = $request->get('name');
        $category->active = $request->get('active');
        $category->save();

        return new CategoryResource($category);
    }
}
