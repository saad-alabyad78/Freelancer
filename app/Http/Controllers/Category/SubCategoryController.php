<?php

namespace App\Http\Controllers\Category;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\SubCategoryResource;
use App\Http\Requests\Category\CreateSubCategoryRequest;
use App\Http\Requests\Category\SubCategorySearchRequest;
use App\Http\Requests\Category\UpdateSubCategoryRequest;
/**
 * @group SubCategory Management
 */
class SubCategoryController extends Controller
{
    /**
     * Search SubCategory.
     * 
     * search by name or sub name 
     * 
     * name = '' to get all
     * 
     * the resault with limit 100 entity if
     * 
     * @unauthenticated
     * 
     * @pagenate
     * 
     */
    public function search(SubCategorySearchRequest $request)
    {
        $subCategories = SubCategory::where('name' , 'LIKE' , '%'.$request->input('name' , '' ).'%')
        ->limit(100) 
        ->get() ;

        return SubCategoryResource::collection($subCategories) ;
    }
    /**
     * Display a listing of the SubCategory.
     * 
     * @unauthenticated
     * 
     * @pagenate
     * 
     */
    public function index()
    {
        $subCategories = SubCategory::paginate(20) ;
        return SubCategoryResource::collection($subCategories) ;
    }

    /**
     * Store SubCategory.
     */
    public function store(CreateSubCategoryRequest $request)
    {
        $subCategory = SubCategory::create($request->validated()) ;
        return SubCategoryResource::make($subCategory) ;
    }

    /**
     * Show SubCategory.
     * 
     * @unauthenticated
     * 
     * 
     */
    public function show(SubCategory $subCategory)
    {
        return SubCategoryResource::make($subCategory) ;
    }

    /**
     * Update SubCategory Name.
     */
    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory)
    {
        $subCategory->update($request->validated()) ;
        return SubCategoryResource::make($subCategory) ;
    }

    /**
     * Remove SubCategory
     * 
     * 
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete() ;
        return response()->json(['message' => 'deleted']) ;
    }
}
