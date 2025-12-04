<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogGeneratorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'tone' => $this->tone,
            'language' => $this->language,
            'word_count' => $this->word_count,
            'audience' => $this->audience,
            'occasion' => $this->occasion,
            'tags' => $this->tags,
            'generated_text' => $this->generated_text,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
