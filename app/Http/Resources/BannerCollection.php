<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                // Check if the item is a main banner
                if ($item instanceof \App\Models\Banner) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'status' => $item->status,
                        'type' => 'main_banner',
                        'content' => [
                            'type' => $item->contents->first()->type ?? null,
                            'path' => $item->contents->first()->path ?? null,
                            'video_link' => $item->video_link,
                            'sub-banner' => $this->getSubBanners(), // Get sub-banners here
                        ],
                    ];
                }

                // If it's a sub-banner, we won't return it here
                return null;
            })->filter(), // Filter out null values
        ];
    }

    private function getSubBanners()
    {
        // Assuming you have access to the sub-banners in the collection
        return $this->collection->filter(function ($item) {
            return $item instanceof \App\Models\Subbanner;
        })->map(function ($subbanner) {
            return [
                'id' => $subbanner->id,
                'title' => $subbanner->title,
                'description' => $subbanner->description,
                'type' => 'sub_banner',
                'content' => [
                    'image_path' => $subbanner->image_path,
                    'video_link' => $subbanner->video_link,
                ],
            ];
        })->values()->all(); // Return the sub-banners as an array
    }
}
