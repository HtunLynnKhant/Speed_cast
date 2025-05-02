<?php

namespace App\Http\Resources;

use App\Enums\ContentTypes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdsResourceCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        // Format the ads and group them by page_id
        $groupedAds = $this->resource->map(function ($ad) {
            return [
                'id' => $ad->id,
                'title' => $ad->title,
                'project_id' => $ad->project_id,
                'ads_type_id' => $ad->ads_type_id,
                'page_id' => $ad->page_id,
                'content_type' => Str::lower(ContentTypes::tryFrom($ad->content_type_id)->name), // Ensure enum is defined
                'content_path' => $ad->content_path,
                'status' => $ad->status,
            ];
        })->groupBy('page_id');

        // Return only the 'pages' key without the success status
        return [
            'pages' => $groupedAds, // Use 'pages' as the key
        ];
    }
}
