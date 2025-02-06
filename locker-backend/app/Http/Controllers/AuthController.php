<?php

namespace App\Http\Controllers;


use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResponse;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return RegisterResponse
     * @throws \Exception
     */
    public function register(Request $request): RegisterResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => ['required|email'],
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);


        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);


        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return new RegisterResponse($success);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): LoginResource
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new ValidationException('Invalid credentials');
        }

        Auth::login($user);

        return new LoginResource(['token' => $user->createToken('API Token')->plainTextToken]);

    }
}
