<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryRequest as CategoryCategoryRequest;
use App\Http\Requests\Category\CreateCategoryRequest as CategoryCreateCategoryRequest;
use App\Http\Requests\Category\DeleteCategoryRequest as CategoryDeleteCategoryRequest;
use App\Http\Requests\Category\EditCategoryRequest as CategoryEditCategoryRequest;
use App\Http\Requests\Category\ViewCategoriesRequest as CategoryViewCategoriesRequest;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class CategoriesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api','authorized.admin'], ['except' => ['index', 'category']]);
    }

    public function index(CategoryViewCategoriesRequest $request){
            $page = $request->page  ?? 1;

            $limit = $request->limit ?? 15;

            $categories = Category::get();

            $maxPages = round($categories->count() / $limit) < 1 ? 1  : round($categories->count() / $limit);

            if($page > $maxPages)
                return response()->json([
                    'status' => 'error',
                    'message' => 'The database doesn\'t have that many categories.'
                ],422);
            
            $sortBy = $request->sortBy ?? 'id';
            $desc = (!$request->desc || $request->desc == 'true' || $request->desc == 1) ? true : false;

            $finalCategories = Category::orderBy($sortBy,($desc) ? 'desc' : 'asc')->paginate($limit);

            return response()->json([
                'status' => 'success',
                'message' => 'Categories listed successfully.',
                'data' => $finalCategories
            ],200);
    }

    public function category(CategoryCategoryRequest $request,$uuid){

        if($uuid)
            $category = Category::where('uuid',$uuid)->first();
        else
            return response()->json([
                'status' => 'error',
                'message' => 'No uuid found.'
            ],422);

        if($category)
            return response()->json([
                'status' => 'success',
                'message' => 'Category printed successfully.',
                'data' => $category
            ],200);
        else
            return response()->json([
                'status' => 'error',
                'message' => 'No category with this uuid.'
            ],422);
    }

    public function edit(CategoryEditCategoryRequest $request, $uuid)
        {
            
        if($uuid)
            $category = Category::where('uuid',$uuid)->first();
        else
            return response()->json([
                'status' => 'error',
                'message' => 'No uuid found.'
            ],422);

        if(!$category)
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found.'
            ],404);


        if($request->title != $category->title)
        {
            $category->title = $request->title;
            $category->slug = Str::slug($request->title);
        }

        
        $category->save();
        
        return response()->json([
            'message' => 'Category with id '.$category->id.' updated successfully',
            'data' => $category,
            'status' => 'success'
        ],200);
        }

        public function delete(CategoryDeleteCategoryRequest $request,$uuid)
        {
            if($uuid)
                $category = Category::where('uuid',$uuid)->first();
            else
                return response()->json([
                    'status' => 'error',
                    'message' => 'No uuid found.'
                ],422);

            if(!$category)
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found.'
                ],404);

            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully.',
                'status' => 'success',
            ],200);
        }

        public function create(CategoryCreateCategoryRequest $request){
            try{
                $category = Category::create([
                    'uuid' => Uuid::uuid4()->toString() ?? '',
                    'title' => $request->title,
                    'slug' => Str::slug($request->title)
                ]);
            }catch(\Exception $e)
            {
                return response()->json([
                    'message' =>'Couldn\'t create category.',
                    'status' => "error",
                ],401);
            }

            return response()->json([
                'message' => 'Category created successfully.',
                'data' => $category,
                'status' => 'success',
            ],200);

        }
}
