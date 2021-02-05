<?php

namespace App\Http\Middleware;

use App\Post;
use Closure;

class PostAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $post = Post::where('id', $request->route('id'))->first();
        if(!$post) {
            return response()->json([
                'success' => false,
                'error' => 'post_not_found'
            ], 404);
        }

        if(!$post->canUserAccess(auth('api')->user()->id)) {
            return response()->json([
                'success' => false,
                'error' => 'not_permitted'
            ], 403);
        }

        return $next($request);
    }
}
