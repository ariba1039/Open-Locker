<?php

namespace App\Http\Resources;

use App\Models\User;
use Dedoc\Scramble\Attributes\SchemaName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
#[SchemaName('TokenResponse')]
class TokenResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->resource->createToken('auth_token')->plainTextToken,
            'name' => $this->resource->name,
            'verified' => $this->resource->hasVerifiedEmail(),
        ];
    }
}
