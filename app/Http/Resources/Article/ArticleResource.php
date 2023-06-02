<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\AuthorResource;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Article
 */
class ArticleResource extends JsonResource
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
            'author' => new AuthorResource($this->author),
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'text' => $this->text,
            'created_at' => Carbon::parse($this->created_at)->format('H:i d.m.Y')
        ];
    }
}
