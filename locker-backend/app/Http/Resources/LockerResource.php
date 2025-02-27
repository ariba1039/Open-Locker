<?php

namespace App\Http\Resources;

use Dedoc\Scramble\Attributes\SchemaName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Locker $resource
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
            'id' => $this->id,
            'is_open' => $this->isOpen,
        ];
    }
}
