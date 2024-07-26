<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicController extends Controller
{
    public function index(): JsonResource
    {
        return TopicResource::collection(Topic::all()->loadCount("threads"));
    }

    public function show(Topic $topic): JsonResource
    {
        return new TopicResource($topic->loadCount('threads')->load('threads'));
    }

    public function store(StoreTopicRequest $request): JsonResource
    {
        $topic = Topic::create($request->validated());
        return new TopicResource($topic);
    }

    public function update(UpdateTopicRequest $request, Topic $topic): JsonResource
    {
        $topic->update($request->validated());
        return new TopicResource($topic->loadCount('threads')->load('threads'));
    }

    public function destroy(Topic $topic): JsonResource
    {
        // deletes all messages in threads related to the topic being deleted
        foreach ($topic->threads as $thread){
            /* I'm not sure why this works even with the next line commented out
            Deleting an individual thread that has replies doesn't work
            without setting 'reply_to' to null (foreignId constraint)
            but deleting all posts in a thread works
            even with some messages replying to other ones */

            /* Maybe it would crash without the next line if replies could be made across different topics
            I'm leaving it here just in case */
            $thread->messages()->update(['reply_to' => null]);

            $thread->messages()->delete();
        }

        $topic->threads()->delete();
        $topic->delete();
        return new TopicResource($topic);
    }
}
