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
        Schema::create('users_like_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')
                ->nullable(false)
            ;
            $table->uuid('post_id')
                ->nullable(false)
            ;
            $table->timestamps();

            $table->unique(['user_id', 'post_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
            ;
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_like_posts');
    }
};
