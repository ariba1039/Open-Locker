<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Item;
use App\Models\ItemLoan;
use App\Models\User;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Konstruktor für den AdminController
     */
    public function __construct()
    {
        // In Laravel 11 wird die Middleware in den Routen angewendet
    }

    /**
     * Gibt eine Liste aller Benutzer zurück
     *
     * @response AnonymousResourceCollection<UserResource>
     */
    public function getAllUsers(): AnonymousResourceCollection
    {
        return UserResource::collection(User::all());
    }

    /**
     * Macht einen Benutzer zum Administrator
     */
    public function makeAdmin(Request $request, User $user): JsonResponse
    {
        if (! $user) {
            return response()->json([
                'message' => __('User not found.'),
            ], 404);
        }

        if ($user->isAdmin()) {
            return response()->json([
                'message' => __('User is already an administrator.'),
            ], 400);
        }

        $user->makeAdmin();

        return response()->json([
            'message' => __('User has been successfully appointed as administrator.'),
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Entfernt Administratorrechte von einem Benutzer
     */
    public function removeAdmin(Request $request, User $user): JsonResponse
    {
        if (! $user) {
            return response()->json([
                'message' => __('User not found.'),
            ], 404);
        }

        if (! $user->isAdmin()) {
            return response()->json([
                'message' => __('User is not an administrator.'),
            ], 400);
        }

        // Verhindere, dass ein Admin sich selbst die Rechte entzieht
        if (Auth::id() === $user->id) {
            return response()->json([
                'message' => __('You cannot remove your own administrator rights.'),
            ], 400);
        }

        $user->removeAdmin();

        return response()->json([
            'message' => __('Administrator rights have been successfully removed.'),
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Gibt Statistiken über das System zurück
     */
    public function getStatistics(): JsonResponse
    {

        $totalUsers = User::count();
        $totalItems = Item::count();
        $totalLoans = ItemLoan::count();
        $activeLoans = ItemLoan::whereNull('returned_at')->count();

        return response()->json([
            'statistics' => [
                /** @var int $totalUsers Gesamtzahl der Benutzer */
                'total_users' => $totalUsers,
                /** @var int $totalItems Gesamtzahl der Gegenstände */
                'total_items' => $totalItems,
                /** @var int $totalLoans Gesamtzahl der Ausleihen */
                'total_loans' => $totalLoans,
                /** @var int $activeLoans Anzahl der aktiven Ausleihen */
                'active_loans' => $activeLoans,
            ],
        ]);
    }
}
