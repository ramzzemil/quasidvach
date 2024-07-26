<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function index(): JsonResource
    {
        return PostResource::collection(Post::all()->loadCount('messages')->loadCount('replies'));
    }

    public function show(Post $post): JsonResource
    {
        return new PostResource($post->loadCount('messages')->loadCount('replies'));
    }

    public function showMessages(Post $post): JsonResource
    {
        if ($post->parent_type == 'Topic'){
            return PostResource::collection($post->messages->loadCount('replies'));
        }
        throw ValidationException::withMessages(["Requested post is not a thread"]);
    }

    public function showReplies(Post $post): JsonResource
    {
        return PostResource::collection($post->replies);
    }

    public function showReplyTo(Post $post): JsonResource
    {
        if ($post->parent_type == 'Post'){
            return new PostResource($post->replyingTo);
        }
        throw ValidationException::withMessages(["Requested post is not a message"]);

    }

    public function storeThread(StoreThreadRequest $request, Topic $topic): JsonResource
    {
        $post = Post::create([
            ...$request->validated(),
            'reply_to' => null,
            'parent_type' => 'Topic',
            'parent_id' => $topic->id,
        ]);
        return new PostResource($post);
    }

    public function storeMessage(StoreMessageRequest $request, Post $post): JsonResource
    {
        $validatedData = $request->validated();
        $validatedData['parent_type'] = 'Post';

        // The following code resolves correct parent_id for the message
        if ($post->parent_type == 'Topic'){
            $validatedData['parent_id'] = $post->id;
        }elseif ($post->parent_type == 'Post'){
            $validatedData['parent_id'] = $post->parent_id;
        }else{
            throw ValidationException::withMessages(["Wrong parent type in post"]);
        }

        // if 'reply_to' is set there is a check that post replied to in the same thread or is the thread post itself
        if (isset($validatedData['reply_to']))
        {
            // checks that the post replied to is in the same thread or is the thread post itself
            $reply_in_the_same_thread = $validatedData['parent_id'] == $validatedData['reply_to'] ||
                                        Post::find($validatedData['parent_id'])->messages
                                            ->contains($validatedData['reply_to']);
            if (!$reply_in_the_same_thread){
                throw ValidationException::withMessages(['reply_to' => "Replies can be only in the same thread as the message replied to"]);
            }
        }
        return new PostResource(Post::create($validatedData)->load('replyingTo'));
    }

    # route to this is disabled in api.php
    public function store(StorePostRequest $request): JsonResource
    {
        $post = Post::create($request->validated());
        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResource
    {
        $post->update($request->validated());
        return new PostResource($post->loadCount('messages')->loadCount('replies'));
    }

    public function destroy(Post $post): JsonResource
    {
        /* in case there are replies 'reply_to' is set to null
        because of foreign key constraints */
        $post->replies()->update(['reply_to' => null]);
        if ($post->parent_type == 'Topic'){
            $post->messages()->update(['reply_to' => null]);
            $post->messages()->delete();
        }
        $post->delete();
        return new PostResource($post);
    }
}
