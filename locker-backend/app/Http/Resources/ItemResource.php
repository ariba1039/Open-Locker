<?php

namespace App\Http\Resources;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Item $resource
 */
class ItemResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( Request $request ): array {
        return [ "id"          => $this->resource->id,
                 "name"        => $this->resource->name,
                 "description" => $this->resource->description,
                 "image_path"  => $this->resource->image_path
        ];
    }
}
