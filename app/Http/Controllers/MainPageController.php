<?php

namespace App\Http\Controllers;

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
        $posts = Post::get();

        return $this->listItems($posts, $page, $limit, 'Posts listed successfully.');
    }

    /**
     * JSON response.
     *
     * @return JsonResponse|array<mixed>
     */
    public function post(string $uuid)
    {
        $post = Post::where('uuid', $uuid)->first();
        return $this->respondWithItem($post, 'Post printed successfully.', 'No post with this uuid.');
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
        $valid = $this->parseBoolean($request->valid);
        $promotions = $this->getPromotions($valid);

        return $this->listItems($promotions, $page, $limit, 'Promotions listed successfully.');
    }

    private function parseBoolean($value): bool
    {
        return $value === 'true' || $value === 1;
    }

    private function getPromotions(bool $valid)
    {
        if ($valid) {
            return Promotion::where('metadata->valid_from', '<', now())->where('metadata->valid_to', '>', now());
        }
        return Promotion::where('metadata->valid_from', '>', now())->where('metadata->valid_to', '<', now());
    }

    private function listItems($items, int $page, int $limit, string $successMessage)
    {
        $maxPages = max(1, ceil($items->count() / $limit));

        if ($page > $maxPages) {
            return $this->errorResponse('The database doesn\'t have that many items.', 422);
        }

        $sortBy = request()->input('sortBy', 'id');
        $desc = $this->parseBoolean(request()->input('desc'));

        $items = $items->orderBy($sortBy, $desc ? 'desc' : 'asc')->paginate($limit);

        return $this->successResponse($successMessage, $items);
    }

    private function respondWithItem($item, string $successMessage, string $errorMessage)
    {
        if ($item) {
            return $this->successResponse($successMessage, $item);
        }
        return $this->errorResponse($errorMessage, 422);
    }

    private function successResponse(string $message, $data): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    private function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }
}
