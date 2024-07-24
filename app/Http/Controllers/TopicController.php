<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use \Illuminate\Validation\Rule;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::all();
        return response()->json($topics, 200);
    }

    public function show(Topic $topic)
    {
        return response()->json($topic, 200);
    }

    public function show_threads(Topic $topic)
    {
        return response()->json($topic->threads, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(['name' => 'required|string|unique:topics']);
        $topic = Topic::create($validatedData);
        return response()->json($topic, 201);
    }

    public function update(Request $request, Topic $topic)
    {
        $validatedData = $request->validate([
            /* Rule::unique('topics')->ignore($topic->id) ignores the name of topic being renamed
            so it's possible to assign the same name (just in case) */
            'name' => ['required', 'string', Rule::unique('topics')->ignore($topic->id)]
        ]);
        $topic->update($validatedData);
        return response()->json($topic, 202);
    }

    public function destroy(Topic $topic)
    {
        // deletes all messages in threads related to the topic being deleted
        foreach ($topic->threads as $thread){
            /* I'm not sure why this works even with the next line commented out
            Deleting an individual thread that has replies doesn't work
            without setting 'reply_to' to null (foreignId constaint)
            but deleting all posts in a thread works
            even with some messages replying to other ones */

            /* Maybe it would crash without the next line if replies could be made across different topics
            I'm leaving it here just in case */
            $thread->messages()->update(['reply_to' => null]);

            $thread->messages()->delete();
        }

        $topic->threads()->delete();
        $topic->delete();
        return response()->json(null, 204);
    }
}
