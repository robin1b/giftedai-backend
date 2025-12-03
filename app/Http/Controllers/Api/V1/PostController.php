<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\BlogPosts;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(BlogPosts::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data= $request -> validated();   
        $data["author_id"] = 1 ;
       $blogPost = BlogPosts::create($data);
        
        return response()-> json($blogPost, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blogposts $post)
    {
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request -> validated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPosts $post)
    {
        $post->delete();
        return response()->noContent();
    }
}
