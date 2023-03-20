<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\StoreRequest;
use App\Http\Requests\Api\Post\UpdateRequest;
use App\Http\Resources\Post\PostResourse;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(StoreRequest $request)
    {

        $data = $request->validated();

        if (!empty($data['image'])) {
            $path = Storage::disk('local')->put('/images', $data['image']);
            $data['image_url'] = $path;
        }
        unset($data['image']);

        $post = Post::create($data);

        return PostResourse::make($post)->resolve();

    }

    public function update(UpdateRequest $request, Post $post)
    {

        $data = $request->validated();

        if (!empty($data['image'])) {
            $path = Storage::disk('local')->put('/images', $data['image']);
            $data['image_url'] = $path;
        }
        unset($data['image']);

        $post->update($data);

        /*$post = $post->fresh();*/

        return PostResourse::make($post)->resolve();

    }

    public function index()
    {
        $posts = Post::all();
        return PostResourse::collection($posts)->resolve();
    }

    public function show(Post $post)
    {
        return PostResourse::make($post)->resolve();
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'massage' => 'deleted'
        ]);

    }
}
