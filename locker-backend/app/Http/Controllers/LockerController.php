<?php

namespace App\Http\Controllers;

use App\Http\Resources\LockerResource;
use App\Models\Locker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LockerController extends Controller
{
    /**
     * Returns a list of all available lockers
     *
     * @response AnonymousResourceCollection<LockerResource>
     */
    public function index(): AnonymousResourceCollection
    {
        return LockerResource::collection(Locker::all());
    }

    /**
     * Manually opens a locker
     */
    public function openLocker(Request $request, Locker $locker): JsonResponse
    {

        return response()->json([
            'status' => true,
            'message' => __('Not yet implemented'),
        ]);

        //        if ( $success ) {
        //            return response()->json( [
        //                'status'  => true,
        //                'message' => __( 'Locker successfully opened.' ),
        //            ] );
        //        }
        //
        //        return response()->json( [
        //            'status'  => false,
        //            'message' => __( 'Failed to open locker.' ),
        //        ], 500 );
    }
}
