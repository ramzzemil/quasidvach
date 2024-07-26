<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    // this avoids creating 'updated_at' field while using standard 'created_at'
    public $timestamps = ["created_at"];
    const UPDATED_AT = null;

    protected $fillable = [
        'body',
        'parent_id',
        'parent_type',
        'reply_to'
    ];

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(self::class, 'parent');
    }

    public function replyingTo(): BelongsTo
    {
        return $this->belongsTo(self::class, 'reply_to')->withDefault();
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'reply_to');
    }
}
