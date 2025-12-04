<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogGeneratorResource;
use App\Models\BlogGenerator;
use App\Models\BlogPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlogGenerationController extends Controller
{
    /**
     * 1. Generate AI blog content based on user input
     */

    public function index()
    {
        $generators = BlogGenerator::where('user_id', request()->user()->id)->paginate();
        return BlogGeneratorResource::collection($generators);
    }

    /**
     * Generate blog content using OpenAI
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'tone' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:10',
            'word_count' => 'nullable|integer|min:50|max:5000',
            'audience' => 'nullable|string|max:255',
            'occasion' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
        ]);

        // Save generator metadata
        $generator = BlogGenerator::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'] ?? null,
            'tone' => $validated['tone'] ?? null,
            'language' => $validated['language'] ?? 'nl',
            'word_count' => $validated['word_count'] ?? null,
            'audience' => $validated['audience'] ?? null,
            'occasion' => $validated['occasion'] ?? null,
            'tags' => $validated['tags'] ?? [],
            'status' => 'generating',
        ]);

        // Create AI prompt dynamically
        $prompt = $this->buildPrompt($generator);

        // Store prompt into DB
        $generator->update(['prompt' => $prompt]);

        // Send to OpenAI
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-4.1",
                "messages" => [
                    ["role" => "system", "content" => "You are a professional SEO blog writer."],
                    ["role" => "user", "content" => $prompt]
                ],
            ]);

        // Extract result
        $text = $response['choices'][0]['message']['content'] ?? null;

        // Save AI output to DB
        $generator->update([
            'generated_text' => $text,
            'raw_response' => $response->json(),
            'status' => 'completed'
        ]);

        return (new BlogGeneratorResource($generator))->additional(['message' => 'Blog content generated successfully.']);
    }

    /**
     * 2. Turn AI content into a blog post
     */
    public function saveAsBlogPost(Request $request)
    {
        $validated = $request->validate([
            'generator_id' => 'required|exists:blog_generators,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $generator = BlogGenerator::findOrFail($validated['generator_id']);

        // Save final blogpost
        $post = BlogPosts::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'author_id' => $request->user()->id
        ]);

        // Link generator → post
        $generator->update(['blog_post_id' => $post->id]);

        return response()->json([
            'message' => 'Blog post created successfully.',
            'post' => $post
        ], 201);
    }

    /**
     * Builds the OpenAI prompt from user input
     */
    private function buildPrompt(BlogGenerator $generator): string
    {
        $title = $generator->title ?? 'Untitled';
        $tone = $generator->tone ?? 'neutral';
        $language = $generator->language ?? 'nl';
        $wordCount = $generator->word_count ?? 800;
        $audience = $generator->audience ?? 'general readers';
        $occasion = $generator->occasion ?? 'none';
        $tags = implode(', ', $generator->tags ?? []);

        return "
Write a full blog post.

Title: {$title}
Audience: {$audience}
Occasion: {$occasion}
Tone: {$tone}
Language: {$language}
Word Count: {$wordCount}
Tags: {$tags}

The blog must include:
- An engaging introduction
- A structured list of gift ideas
- Detailed explanations
- A conclusion with a CTA

Write in clean paragraph format.
";
    }
}
