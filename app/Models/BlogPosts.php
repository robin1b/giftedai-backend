<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPosts extends Model
{
    protected $fillable = ['title', 'content', 'author_id'];
}
