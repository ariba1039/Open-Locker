<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ItemController extends Controller
{
    /**
     * Get all Items.
     *
     * @response AnonymousResourceCollection<ItemResource>
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return ItemResource::collection(Item::all());
    }
}
