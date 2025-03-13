<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('up_vote_count')->default(0);
            $table->integer('down_vote_count')->default(0);
            $table->integer('rating')->virtualAs('up_vote_count - down_vote_count');
            $table->timestamps();

            $table->fullText(['title', 'description'], 'articles_fulltext_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
