<?php

namespace App\Http\Resources;

use App\Entities\Locker;
use Dedoc\Scramble\Attributes\SchemaName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Locker $resource
 * @property-read string $id
 * @property-read bool $isOpen
 */
#[SchemaName('Locker')]
class LockerResource extends JsonResource
{
    /**
     * Transform the Locker entity into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'is_open' => false,
        ];
    }
}
