<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts, 200);
    }

    public function show(Post $post)
    {
        return response()->json($post, 200);
    }

    public function show_messages(Post $post)
    {
        if ($post->parent_type == 'Topic'){
            return response()->json($post->messages, 200);
        }
        return response()->json("Requested post is not a thread", 400);
    }

    public function show_replies(Post $post)
    {
        return response()->json($post->replies, 200);
    }

    public function show_reply_to(Post $post)
    {
        return response()->json($post->replying_to, 200);
    }

    public function store_thread(Request $request, Topic $topic)
    {
        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);

        /* A separate route is used to store threads
        so the following fields are not to be filled
        in incoming requests */
        $validatedData['reply_to'] = null;
        $validatedData['parent_type'] = 'Topic';
        $validatedData['parent_id'] = $topic->id;

        $post = Post::create($validatedData);
        return response()->json($post, 201);
    }

    public function store_message(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'body' => 'required|string',
            'reply_to' => 'numeric|nullable',
        ]);
        $validatedData['parent_type'] = 'Post';

        // The following code resolves correct parent_id for the message
        if ($post->parent_type == 'Topic'){
            $validatedData['parent_id'] = $post->id;
        }elseif ($post->parent_type == 'Post'){
            $validatedData['parent_id'] = $post->parent_id;
        }else{
            return response()->json("wrong parent type in post", 500);
        }

        // if 'reply_to' is set there is a check that post replied to in the same thread or is the thread post itself
        if (isset($validatedData['reply_to']))
        {
            // checks that the post replied to is in the same thread or is the thread post itself
            $reply_in_the_same_thread = $validatedData['parent_id'] == $validatedData['reply_to'] ||
                                        Post::find($validatedData['parent_id'])->messages
                                            ->contains($validatedData['reply_to']);
            if (!$reply_in_the_same_thread){
                return response()->json("Replies can be only in the same thread as the message replied to", 400);
            }
        }
        $post = Post::create($validatedData);
        return response()->json($post, 201);
    }

    # route to this is disabled in api.php
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'body' => 'required|string',
            'reply_to' => 'numeric|nullable',
            'parent_id' => 'required|numeric',
            'parent_type' => 'required|string',
        ]);
        $post = Post::create($validatedData);
        return response()->json($post, 201);
    }

    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);
        $post->update($validatedData);
        return response()->json($post, 202);
    }

    public function destroy(Post $post)
    {
        /* in case there are replies 'reply_to' is set to null
        because of foreign key constraints */
        $post->replies()->update(['reply_to' => null]);
        if ($post->parent_type == 'Topic'){
            $post->messages()->update(['reply_to' => null]);
            $post->messages()->delete();
        }
        $post->delete();
        return response()->json(null, 200);
    }
}
