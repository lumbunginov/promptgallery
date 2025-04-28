<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageStyleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->when($this->image, $this->image),
            'code' => $this->code,
            'description' => $this->description,
            'category' => $this->when($this->category, [
                'name' => $this->category->category
            ]),
            'tags' => $this->whenLoaded('tags', function() {
                return $this->tags->map(function($tag) {
                    return [
                        'id' => $tag->id,
                        'tag' => $tag->tag
                    ];
                });
            }),
            'variations' => $this->variations()->map(function($variation) {
                return [
                    'id' => $variation->id,
                    'title' => $variation->title,
                    'code' => $variation->code,
                    'image' => $variation->image
                ];
            })
        ];
    }
} 