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
            $table->uuid('id')
                ->unique()
            ;
            $table->string('title', length: 255)
                ->nullable(false)
            ;
            $table->string('slug', length: 255)
                ->unique()
                ->nullable(false)
            ;
            $table->timestamp('published_at')
                ->nullable()
            ;
            $table->timestamps();
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
