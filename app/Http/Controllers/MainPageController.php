<?php

namespace App\Http\Controllers;

use App\Http\Requests\Main\PostRequest;
use App\Http\Requests\Main\PostsRequest;
use App\Http\Requests\Main\PromotionsRequest;
use App\Models\Post;
use App\Models\Promotion;
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
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 15;
        $finalPosts = $this->getPaginatedPosts($request, $page, $limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Posts listed successfully.',
            'data' => $finalPosts
        ], 200);
    }

    /**
     * JSON response for retrieving paginated posts.
     *
     * @param  PostsRequest  $request
     * @param  int  $page
     * @param  int  $limit
     * @return \Illuminate\Pagination\Paginator
     */
    private function getPaginatedPosts(PostsRequest $request, $page, $limit)
    {
        $sortBy = $request->sortBy ?? 'id';
        $desc = $this->getDescValue($request);

        return Post::orderBy($sortBy, $desc ? 'desc' : 'asc')->paginate($limit);
    }

    /**
     * Get the boolean value of the 'desc' parameter.
     *
     * @param  PostsRequest  $request
     * @return bool
     */
    private function getDescValue(PostsRequest $request)
    {
        return $request->desc === 'true' || $request->desc === 1;
    }

    /**
     * JSON response.
     *
     * @return JsonResponse|array<mixed>
     */
    public function post(PostRequest $request, string $uuid)
    {
        $post = $this->getPostByUuid($uuid);

        if ($post) {
            return response()->json([
                'status' => 'success',
                'message' => 'Post printed successfully.',
                'data' => $post
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No post with this uuid.'
        ], 422);
    }

    /**
     * Get a post by UUID.
     *
     * @param  string  $uuid
     * @return \App\Models\Post|null
     */
    private function getPostByUuid(string $uuid)
    {
        return Post::where('uuid', $uuid)->first();
    }

    /**
     * JSON response.
     *
     * @return JsonResponse|array<mixed>
     */
    public function promotions(PromotionsRequest $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 15;
        $valid = $this->getValidValue($request);
        $promotions = $this->getPromotionsByValidity($valid);

        return response()->json([
            'status' => 'success',
            'message' => 'Promotions listed successfully.',
            'data' => $promotions
        ], 200);
    }

    /**
     * Get the boolean value of the 'valid' parameter.
     *
     * @param  PromotionsRequest  $request
     * @return bool
     */
    private function getValidValue(PromotionsRequest $request)
    {
        return $request->valid === 'true' || $request->valid === 1;
    }

    /**
     * Get promotions by validity.
     *
     * @param  bool  $valid
     * @return \Illuminate\Pagination\Paginator
     */
    private function getPromotionsByValidity(bool $valid)
    {
        $query = Promotion::orderByDesc('id');

        if ($valid) {
            $query->where('metadata->valid_from', '<', now())->where('metadata->valid_to', '>', now());
        } else {
            $query->where('metadata->valid_from', '>', now())->where('metadata->valid_to', '<', now());
        }

        return $query->paginate($this->getPromotionsPerPage());
    }

    /**
     * Get the number of promotions to display per page.
     *
     * @return int
     */
    private function getPromotionsPerPage()
    {
        return 15; // You can adjust this as needed.
    }
}
