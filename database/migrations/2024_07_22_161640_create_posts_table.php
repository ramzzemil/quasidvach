<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            // not usings standard timestamps to avoid creating 'updated_at' field
            $table->timestamp('created_at');
            $table->text('body');
            $table->morphs('parent');
            // creates 'reply_to' and constrains it so that it can reference only existing posts
            $table->foreignId('reply_to')->nullable()
                    ->references('id')->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
