<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BlogPosts;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BlogPosts::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data= $request -> validate([
            'title' => '|required|string',
            'content' => 'sometimes|required|string',
        ]);   
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
        $request -> validate([
            'title' => '|required|string',
            'content' => 'sometimes|required|string',
        ]);
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
