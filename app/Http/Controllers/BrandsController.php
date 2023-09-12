<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\BrandRequest;
use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\DeleteBrandRequest;
use App\Http\Requests\Brand\EditBrandRequest;
use App\Http\Requests\Brand\ViewBrandsRequest;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class BrandsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api','authorized.admin'], ['except' => ['index', 'brand']]);
    }

    public function index(ViewBrandsRequest $request){
            $page = $request->page  ?? 1;

            $limit = $request->limit ?? 15;

            $brands = Brand::get();

            $maxPages = round($brands->count() / $limit) < 1 ? 1  : round($brands->count() / $limit);

            if($page > $maxPages)
                return response()->json([
                    'status' => 'error',
                    'message' => 'The database doesn\'t have that many brands.'
                ],422);
            
            $sortBy = $request->sortBy ?? 'id';
            $desc = (!$request->desc || $request->desc == 'true' || $request->desc == 1) ? true : false;

            $finalBrands = Brand::orderBy($sortBy,($desc) ? 'desc' : 'asc')->paginate($limit);

            return response()->json([
                'status' => 'success',
                'message' => 'Brands listed successfully.',
                'data' => $finalBrands
            ],200);
    }

    public function brand(BrandRequest $request,$uuid){

        if($uuid)
            $brand = Brand::where('uuid',$uuid)->first();
        else
            return response()->json([
                'status' => 'error',
                'message' => 'No uuid found.'
            ],422);

        if($brand)
            return response()->json([
                'status' => 'success',
                'message' => 'Brand printed successfully.',
                'data' => $brand
            ],200);
        else
            return response()->json([
                'status' => 'error',
                'message' => 'No brand with this uuid.'
            ],422);
    }

    public function edit(EditBrandRequest $request, $uuid)
        {
            
        if($uuid)
            $brand = Brand::where('uuid',$uuid)->first();
        else
            return response()->json([
                'status' => 'error',
                'message' => 'No uuid found.'
            ],422);

        if(!$brand)
            return response()->json([
                'status' => 'error',
                'message' => 'Brand not found.'
            ],404);


        if($request->title != $brand->title)
        {
            $brand->title = $request->title;
            $brand->slug = Str::slug($request->title);
        }

        
        $brand->save();
        
        return response()->json([
            'message' => 'Brand with id '.$brand->id.' updated successfully',
            'data' => $brand,
            'status' => 'success'
        ],200);
        }

        public function delete(DeleteBrandRequest $request,$uuid)
        {
            if($uuid)
                $brand = Brand::where('uuid',$uuid)->first();
            else
                return response()->json([
                    'status' => 'error',
                    'message' => 'No uuid found.'
                ],422);

            if(!$brand)
                return response()->json([
                    'status' => 'error',
                    'message' => 'Brand not found.'
                ],404);

            $brand->delete();

            return response()->json([
                'message' => 'Brand deleted successfully.',
                'status' => 'success',
            ],200);
        }

        public function create(CreateBrandRequest $request){
            try{
                Brand::create([
                    'uuid' => Uuid::uuid4()->toString() ?? '',
                    'title' => $request->title,
                    'slug' => Str::slug($request->title)
                ]);
            }catch(\Exception $e)
            {
                return response()->json([
                    'message' =>'Couldn\'t create brand.',
                    'status' => "error",
                ],401);
            }

            return response()->json([
                'message' => 'Brand created successfully.',
                'status' => 'success',
            ],200);

        }
}
