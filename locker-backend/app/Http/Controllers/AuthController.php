<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegisterResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller {
    /**
     * Register api
     *
     * @throws \Exception
     */
    public function register( Request $request ): JsonResponse {
        $request->validate( [
            'name'     => [ 'required', 'string', 'max:255' ],
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique( User::class ),
            ],
            'password' => [ 'required', 'string', 'min:8', 'confirmed' ],
        ] );

        $user = User::create( [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make( $request->password ),
        ] );


        $token = $user->createToken( 'auth_token' )->plainTextToken;

        return response()->json( [ "token" => $token, "name" => $user->name ] );
    }

    /**
     * Login api
     * @unauthenticated
     */
    public function login( Request $request ): JsonResponse {
        $request->validate( [
            'email'    => 'required|email',
            'password' => 'required',
        ] );

        if ( ! Auth::attempt( $request->only( 'email', 'password' ) ) ) {
            throw ValidationException::withMessages( [ 'email' => [ 'The provided credentials are incorrect.' ] ] );
        }

        $token = $request->user()->createToken( 'auth_token' )->plainTextToken;

        return response()->json( [ 'token' => $token, 'name' => $request->user()->name ] );

    }

    /**
     * Logout api
     */
    public function logout( Request $request ): JsonResponse {
        $request->user()->currentAccessToken()->delete();

        return response()->json( [
            'message' => 'Logged out successfully'
        ] );
    }

    /**
     * Get current User
     */
    public function user( Request $request ): JsonResponse {
        return response()->json( $request->user() );
    }
}
