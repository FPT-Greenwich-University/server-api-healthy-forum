<?php

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchProducts(Request $request)
    {
        try {
            if ($request->has('title')) {
                $posts = Post::with(['image', 'category', 'user'])
                    ->isPublished()
                    ->where('title', 'like', '%' . $request->query('title') . '%')
                    ->paginate(10)
                    ->withQueryString();

                return response()->json($posts);
            }

            return response()->json('No records');
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
