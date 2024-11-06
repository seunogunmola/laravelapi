<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('access_token')->plainTextToken;
            return response()
                ->json(
                    [
                        'status' => true,
                        'message' => 'Login Successful',
                        'data' => [
                            'user' => $user,
                            'token' => $token
                        ]
                    ],
                    200
                );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Invalid Credentials'
                ],
                401
            );
        }
    }

    public function register(Request $request){
        //Validation
        $validated = $request->validate(
            [
                'name'=>'string|required',
                'email'=>'email|required|unique:users',
                'password'=>'string|required|confirmed|min:8',
            ]
        );

        $user = User::create($validated);

        $token = $user->createToken('access_token')->plainTextToken;

        return response()
                ->json(
                    [
                        'status' => true,
                        'message' => 'Registration Successful',
                        'data' => [
                            'user' => $user,
                            'token' => $token
                        ]
                    ],
                    201
                );
    }
}
