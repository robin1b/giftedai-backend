<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogGenerator extends Model
{
    protected $fillable = [
        'user_id',
        'blog_post_id',
        'title',
        'tone',
        'language',
        'word_count',
        'audience',
        'occasion',
        'tags',
        'prompt',
        'generated_text',
        'raw_response',
        'status'
    ];

    protected $casts = [
        'tags' => 'array',
        'raw_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blogPost()
    {
        return $this->belongsTo(BlogPosts::class);
    }
}
