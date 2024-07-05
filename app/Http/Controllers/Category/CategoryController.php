<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
/**
 * @group Category Managment
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of the category.
     * 
     * @unauthenticated
     * 
     * @pagenate
     * 
     */
    public function index()
    {
        $categories = Category::paginate(20) ;
        return CategoryResource::collection($categories) ;
    }

    /**
     * 
     * Store Category.
     */
    public function store(CreateCategoryRequest $request)
    {
        $category = Category::create($request->validated()) ;
        return CategoryResource::make($category) ;
    }

    /**
     * Show Category.
     * 
     * @unauthenticated
     *
     */
    public function show(Category $category)
    {
        return CategoryResource::make($category) ;
    }

    /**
     * Update Category Name.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        
        $category->update($request->validated()) ;
        return CategoryResource::make($category) ;
    }

    /**
     * Remove Category
     * 
     * SubCategory will be removed 
     * 
     */
    public function destroy(Category $category)
    {
        $category->delete() ;
        return response()->json(['message' => 'deleted']) ;
    }
}
