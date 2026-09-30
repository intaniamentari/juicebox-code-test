<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = auth()->user()->posts()->paginate(10);
        return PostResource::collection($posts);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return new PostResource($post);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $post = $request->user()->posts()->create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, int $id)
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->update($request->validated());

        return new PostResource($post->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
