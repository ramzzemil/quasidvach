<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Post;


class Topic extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;

    public function threads(): MorphMany
    {
        return $this->morphMany(Post::class, 'parent');
    }
}
