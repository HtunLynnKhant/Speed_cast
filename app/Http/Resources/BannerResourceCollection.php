<?php

namespace App\Http\Resources;

use App\Enums\ContentTypes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerResourceCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->resource->map(function ($item) {
            $contents = $item->contents->map(function (mixed $content) {
                return [
                    Str::lower(ContentTypes::tryFrom($content->type)->name) => $content->path
                ];
            });
            $banner = [
                'id' => $item->id,
                'title' => $item->title,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'contents' => $contents
            ];

            return $banner;
        })->toArray();
    }
}
