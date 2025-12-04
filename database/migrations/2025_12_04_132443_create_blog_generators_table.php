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
        Schema::create('blog_generators', function (Blueprint $table) {
            $table->id();

            // User who triggered the generation
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Optional link to a created blog post
            $table->foreignId('blog_post_id')
                ->nullable()
                ->constrained('blog_posts')
                ->onDelete('set null');

            // INPUT fields used for generation (traceability)
            $table->string('title')->nullable();            // Optional title used in prompt
            $table->string('tone')->nullable();             // e.g. friendly, formal, funny
            $table->string('language')->default('en');      // ISO code: en, nl, fr, ...
            $table->integer('word_count')->nullable();      // target size
            $table->string('audience')->nullable();         // For who: teens, women, parents...
            $table->string('occasion')->nullable();         // Christmas, birthday, etc.
            $table->json('tags')->nullable();               // ["gifts","budget","kids"]

            // The actual prompt sent to ChatGPT (optional but recommended)
            $table->longText('prompt')->nullable();

            // AI OUTPUT fields
            $table->longText('generated_text')->nullable(); // AI-generated full blog content
            $table->json('raw_response')->nullable();        // full OpenAI response (optional)

            // Status tracking for async/queued generation
            $table->string('status')->default('pending');
            // pending | generating | completed | failed

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_generators');
    }
};
