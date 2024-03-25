<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){

        $UserValidator = Validator::make($request->all(), [
            'name' => 'required | string',
            'email' => 'required | string | email | unique:users',
            'password' => 'required | string | min:6',
        ]);

        if($UserValidator->fails()){
            return response()->json([
                'message' => $UserValidator->errors(),
                'status' => 'error',
            ], 422);
        }
        

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'User created successfully',
            'status' => 'success',
        ], 201);

        if(!$user){
            return response()->json([
                'message' => 'User not created',
                'status' => 'error',
            ], 500);
        }

    } 

    public function login(Request $request){

        $UserValidator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($UserValidator -> fails()){
            return response()->json([
                'message' => $UserValidator->errors(),
                'status' => 'error',
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials',
                'status' => 'error',
            ], 401);
        }
        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'User logged in successfully',
            'status' => 'success',
        ], 200);
    }
}
