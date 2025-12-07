<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\BlogPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();
        $posts = $user->posts()->paginate();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = $request->user()->id;
        $blogPost = BlogPosts::create($data);
        return response()->json(new PostResource($blogPost), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blogposts $post)
    {
        abort_if(Auth::id() !== $post->author_id, 403, 'Unauthorized');
        return response()->json(new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPosts $post)
    {

        abort_if(Auth::id() !== $post->author_id, 403, 'Unauthorized');
        $data = $request->validate(
            [
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
            ]
        );
        $post->update($data);
        return response()->json(new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPosts $post)
    {
        abort_if(Auth::id() !== $post->author_id, 403, 'Unauthorized');
        $post->delete();
        return response()->noContent();
    }
    /**
     * Display a listing of public posts.
     */
    public function publicIndex()
    {
        $posts = BlogPosts::where('is_published', true)
            ->latest()
            ->paginate(10);

        return PostResource::collection($posts);
    }
}
