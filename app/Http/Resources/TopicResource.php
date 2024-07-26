<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'threads_count' => $this->whenCounted("threads"),
            'threads' => PostResource::collection($this->whenLoaded("threads")),
        ];
    }
}
