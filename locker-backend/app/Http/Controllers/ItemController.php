<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Services\LockerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

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

    /**
     * Get all Items from User.
     *
     * @response AnonymousResourceCollection<ItemResource>
     */
    public function getBorrowedItemsFromUser(Request $request): AnonymousResourceCollection
    {
        return ItemResource::collection(Item::where('borrower_id', $request->user()->id)->get());
    }

    /**
     * Borrow a Item
     */
    public function borrowItem(Item $item, Request $request, LockerServiceInterface $lockerService): JsonResponse
    {

        if ($item->borrower_id !== null) {
            return response()->json([
                'status' => false,
                'message' => __('Item is already borrowed'),

            ]);
        }

        DB::transaction(function () use ($lockerService, $item, $request) {
            $item->borrower_id = $request->user()->id;
            $item->save();

            $lockerService->openLocker($item->locker_id);
        });

        return response()->json([
            'status' => true,
            'message' => __('Item borrowed successfully'),
        ]);

    }

    /**
     * Returns a Item
     */
    public function returnItem(Item $item, Request $request, LockerServiceInterface $lockerService): JsonResponse
    {

        if ($item->borrower_id === null) {
            return response()->json([
                'status' => false,
                'message' => __('Item is not borrowed'),
            ]);
        }

        DB::transaction(function () use ($lockerService, $item) {
            $item->borrower_id = null;
            $item->save();

            $lockerService->openLocker($item->locker_id);
        });

        return response()->json([
            'status' => true,
            'message' => __('Item returned successfully'),
        ]);

    }
}
