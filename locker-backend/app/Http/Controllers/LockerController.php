<?php

namespace App\Http\Controllers;

use App\Entities\Locker;
use App\Http\Resources\LockerResource;
use App\Services\LockerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LockerController extends Controller
{
    /**
     * The locker service for interacting with lockers
     */
    protected LockerServiceInterface $lockerService;

    /**
     * Constructor for the LockerController
     */
    public function __construct(LockerServiceInterface $lockerService)
    {
        $this->lockerService = $lockerService;
    }

    /**
     * Returns a list of all available lockers
     *
     * @response AnonymousResourceCollection<LockerResource>
     */
    public function index(): AnonymousResourceCollection
    {
        $lockers = $this->lockerService->getLockerList();

        // Create a collection of lockers with status
        $lockersWithStatus = [];
        foreach ($lockers as $locker) {
            $lockersWithStatus[] = [
                'original' => $locker,
                'isOpen' => $this->lockerService->getLockerStatus($locker->id),
            ];
        }

        return LockerResource::collection(collect($lockersWithStatus)->map(function ($item) {
            return (object) [
                'id' => $item['original']->id,
                'modbusAddress' => $item['original']->modbusAddress,
                'coilRegister' => $item['original']->coilRegister,
                'statusRegister' => $item['original']->statusRegister,
                'isOpen' => $item['isOpen'],
            ];
        }));
    }

    /**
     * Manually opens a locker
     */
    public function openLocker(Request $request, string $lockerId): JsonResponse
    {
        $success = $this->lockerService->openLocker($lockerId);

        if ($success) {
            return response()->json([
                'status' => true,
                'message' => __('Locker successfully opened.'),
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => __('Failed to open locker.'),
        ], 500);
    }
}
