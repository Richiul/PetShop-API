<?php

namespace App\Http\Controllers;

use App\Http\Requests\Main\PostRequest;
use App\Http\Requests\Main\PostsRequest;
use App\Http\Requests\Main\PromotionsRequest;
use App\Models\Post;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MainPageController extends Controller
{
    /**
     * JSON response.
     *
     * @return JsonResponse|array<mixed>
     */
    public function index(PostsRequest $request)
    {

        $page = $request->page  ?? 1;

        $limit = $request->limit ?? 15;

        $posts = Post::get();

        $maxPages = round($posts->count() / $limit) < 1 ? 1  : round($posts->count() / $limit);

        if ($page > $maxPages)
            return response()->json([
                'status' => 'error',
                'message' => 'The database doesn\'t have that many posts.'
            ], 422);

        $sortBy = $request->sortBy ?? 'id';
        $desc = (!$request->desc || $request->desc == 'true' || $request->desc == 1) ? true : false;

        $finalPosts = Post::orderBy($sortBy, ($desc) ? 'desc' : 'asc')->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Posts listed successfully.',
            'data' => $finalPosts
        ], 200);
    }
    /**
     * JSON response.
     *
     * @return JsonResponse|array<mixed>
     */
    public function post(PostRequest $request, string $uuid)
    {

        if ($uuid)
            $post = Post::where('uuid', $uuid)->first();
        else
            return response()->json([
                'status' => 'error',
                'message' => 'No uuid found.'
            ], 422);

        if ($post)
            return response()->json([
                'status' => 'success',
                'message' => 'Post printed successfully.',
                'data' => $post
            ], 200);
        else
            return response()->json([
                'status' => 'error',
                'message' => 'No post with this uuid.'
            ], 422);
    }
    /**
     * JSON response.
     *
     * @return JsonResponse|array<mixed>
     */
    public function promotions(PromotionsRequest $request)
    {

        $page = $request->page  ?? 1;

        $limit = $request->limit ?? 15;

        $valid = (!$request->valid || $request->valid == 'true' || $request->valid == 1) ? true : false;

        if ($valid == true) {
            $promotions = Promotion::where('metadata->valid_from', '<', now())->where('metadata->valid_to', '>', now());
        } else {
            $promotions = Promotion::where('metadata->valid_from', '>', now())->where('metadata->valid_to', '<', now());
        }

        $maxPages = round($promotions->count() / $limit) < 1 ? 1  : round($promotions->count() / $limit);

        if ($page > $maxPages)
            return response()->json([
                'status' => 'error',
                'message' => 'The database doesn\'t have that many promotions.'
            ], 422);

        $sortBy = $request->sortBy ?? 'id';
        $desc = (!$request->desc || $request->desc == 'true' || $request->desc == 1) ? true : false;

        $promotions = $promotions->orderBy($sortBy, ($desc) ? 'desc' : 'asc')->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Promotions listed successfully.',
            'data' => $promotions
        ], 200);
    }
}
