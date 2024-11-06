<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //GET USER CREDENTIALS
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //PERFORM AUTHENTICATION
        if (!Auth::attempt($credentials)) {
            return response()->json(
                [
                    'status'=>false,
                    'message' => 'Invalid credentials'
                ], 
                401);                
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status'=>true,
            'message'=>'Login Successful',
            'access_token' => $token,
        ]);
    }

    public function register(Request $request){
        //Validate Input
        $validated = $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]
        );  
        
        //Create User
        $user = User::create($validated);

        //Generate Token
        $token = $user->createToken('auth_token')->plainTextToken;

        //Return Response
        return response()->json([
            'status'=>true,
            'message'=>'Login Successful',
            'data'=>[
                'user'=>$user,
                'access_token' => $token
            ]            
        ]);        
    }
}
