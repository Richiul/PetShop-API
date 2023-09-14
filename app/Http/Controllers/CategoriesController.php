<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest as CategoryCreateCategoryRequest;
use App\Http\Requests\Category\EditCategoryRequest as CategoryEditCategoryRequest;
use App\Http\Requests\Category\ViewCategoriesRequest as CategoryViewCategoriesRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'authorized.admin'], ['except' => ['index', 'category']]);
    }

    public function index(CategoryViewCategoriesRequest $request)
    {
        $categories = $this->paginateCategories($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Categories listed successfully.',
            'data' => $categories
        ], 200);
    }

    public function category(string $uuid)
    {
        $category = $this->findCategoryByUuid($uuid);

        if (!$category) {
            return $this->categoryNotFoundResponse();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category printed successfully.',
            'data' => $category
        ], 200);
    }

    public function edit(CategoryEditCategoryRequest $request, string $uuid)
    {
        $category = $this->findCategoryByUuid($uuid);

        if (!$category) {
            return $this->categoryNotFoundResponse();
        }

        $this->updateCategory($category, $request->title);

        return response()->json([
            'message' => 'Category with id ' . $category->id . ' updated successfully',
            'data' => $category,
            'status' => 'success'
        ], 200);
    }

    public function delete(string $uuid)
    {
        $category = $this->findCategoryByUuid($uuid);

        if (!$category) {
            return $this->categoryNotFoundResponse();
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
            'status' => 'success',
        ], 200);
    }

    public function create(CategoryCreateCategoryRequest $request)
    {
        try {
            $category = $this->createCategory($request->title);
        } catch (\Exception $e) {
            return $this->categoryCreationFailedResponse();
        }

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category,
            'status' => 'success',
        ], 200);
    }

    private function paginateCategories(CategoryViewCategoriesRequest $request)
    {
        $limit = $request->limit ?? 15;
        $sortBy = $request->sortBy ?? 'id';
        $desc = (!$request->desc || $request->desc === 'true' || $request->desc === 1) ? 'asc' : 'desc';

        return Category::orderBy($sortBy, $desc)->paginate($limit);
    }

    private function findCategoryByUuid(string $uuid)
    {
        if ($uuid) {
            return Category::where('uuid', $uuid)->first();
        }

        return null;
    }

    private function updateCategory(Category $category, string $title)
    {
        if ($title !== $category->title) {
            $category->title = $title;
            $category->slug = Str::slug($title);
            $category->save();
        }
    }

    private function createCategory(string $title)
    {
        return Category::create([
            'uuid' => Uuid::uuid4()->toString(),
            'title' => $title,
            'slug' => Str::slug($title)
        ]);
    }

    private function categoryNotFoundResponse()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'No category with this uuid.'
        ], 422);
    }

    private function categoryCreationFailedResponse()
    {
        return response()->json([
            'message' => 'Couldn\'t create category.',
            'status' => 'error',
        ], 401);
    }
}
