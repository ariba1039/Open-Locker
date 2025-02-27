<?php

namespace App\Http\Resources;

use App\Models\Item;
use App\Models\ItemLoan;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\SchemaName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $id
 * @property-read Item $item
 * @property-read int $user_id
 * @property-read Carbon|null $borrowed_at
 * @property-read Carbon|null $returned_at
 *
 * @mixin ItemLoan
 */
#[SchemaName('ItemLoan')]
class ItemLoanResource extends JsonResource
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
            'item' => new ItemResource($this->item),
            'user_id' => $this->user_id,
            'borrowed_at' => $this->borrowed_at,
            'returned_at' => $this->returned_at,
        ];
    }
}
