<?php

use Domain\Article\Enums\VoteTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_article_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->enum('vote_type', array_column(VoteTypeEnum::cases(), 'value'))->default('up');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['user_id', 'article_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_article_likes');
    }
};
