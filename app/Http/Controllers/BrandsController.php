<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\EditBrandRequest;
use App\Http\Requests\Brand\ViewBrandsRequest;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class BrandsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'authorized.admin'], ['except' => ['index', 'brand']]);
    }

    public function index(ViewBrandsRequest $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 15;

        $query = Brand::query();
        $sortBy = $request->sortBy ?? 'id';
        $desc = (!$request->desc || $request->desc == 'true' || $request->desc == 1) ? 'asc' : 'desc';

        $brands = $query->orderBy($sortBy, $desc)->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Brands listed successfully.',
            'data' => $brands
        ], 200);
    }

    public function brand(string $uuid)
    {
        $brand = $this->findBrandByUuid($uuid);

        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'No brand with this uuid.'
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Brand printed successfully.',
            'data' => $brand
        ], 200);
    }

    public function edit(EditBrandRequest $request, string $uuid)
    {
        $brand = $this->findBrandByUuid($uuid);

        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'No brand with this uuid.'
            ], 422);
        }

        if ($request->title !== $brand->title) {
            $brand->title = $request->title;
            $brand->slug = Str::slug($request->title);
            $brand->save();
        }

        return response()->json([
            'message' => 'Brand with id ' . $brand->id . ' updated successfully',
            'data' => $brand,
            'status' => 'success'
        ], 200);
    }

    public function delete(string $uuid)
    {
        $brand = $this->findBrandByUuid($uuid);

        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'No brand with this uuid.'
            ], 422);
        }

        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully.',
            'status' => 'success',
        ], 200);
    }

    public function create(CreateBrandRequest $request)
    {
        try {
            Brand::create([
                'uuid' => Uuid::uuid4()->toString(),
                'title' => $request->title,
                'slug' => Str::slug($request->title)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Couldn\'t create brand.',
                'status' => 'error',
            ], 401);
        }

        return response()->json([
            'message' => 'Brand created successfully.',
            'status' => 'success',
        ], 200);
    }

    private function findBrandByUuid(string $uuid)
    {
        if ($uuid) {
            return Brand::where('uuid', $uuid)->first();
        }

        return null;
    }
}
