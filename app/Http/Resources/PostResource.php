<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'parent_id' => $this->parent_id,
            'parent_type' => $this->parent_type,
            'thread_messages_count' => $this->when($this->parent_type == 'Topic', $this->whenCounted('messages')),
            'thread_messages' => $this->when($this->parent_type == 'Topic', $this->whenLoaded('messages')),
            'reply_to' => $this->whenNotNull($this->reply_to),
            'replying_to' => $this->when($this->reply_to != null, $this->whenLoaded('replyingTo')),
            'replies_count' => $this->whenCounted('replies'),
            'replies' => $this->whenLoaded('replies'),
            'created_at' => $this->created_at,
        ];
    }
}
