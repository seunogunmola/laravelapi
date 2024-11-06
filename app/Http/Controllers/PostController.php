<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts =  Post::latest()->paginate(10);
        return response()
            ->json(
                [
                    'status'=>true,
                    'message'=>'Posts Retrieved Successfully',
                    'data'=>$posts
                ],
                200
            );        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Post Validation 
        $validated = $request->validate(
            [
                'title'=>'string|required|unique:posts',
                'author'=>'string|required',
                'excerpt'=>'string|required',
                'text'=>'string|required',
            ]
        );

        $post = Post::create($validated);

        return response()
            ->json(
                [
                    'status'=>true,
                    'message'=>'Post Created Successfully',
                    'data'=>$post
                ],
                201
            );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
    
            return response()->json([
                'status' => true,
                'message' => 'Post Retrieved Successfully',
                'data' => $post
            ], 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Post Not Found'
            ], 404);
        }        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Post Validation 
        $validated = $request->validate(
            [
                'title'=>'string|required|unique:posts,title,'.$post->id,
                'author'=>'string|required',
                'excerpt'=>'string|required',
                'text'=>'string|required',
            ]
        );

        $post->update($validated);

        return response()
            ->json(
                [
                    'status'=>true,
                    'message'=>'Post Updated Successfully',
                    'data'=>$post
                ],
                200
            );        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()
            ->json(
                [
                    'status'=>true,
                    'message'=>'Post Deleted Successfully',
                ],
                200
            );         
    }
}
