<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Login Method
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Email and password are required',
            ], 400);
        }

        $user = DB::table('users')
                ->where('email', $request->email)
                ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        // JWT token
        $key = env('JWT_SECRET');  
        $payload = [
            'user_id' => $user->user_id,
            'email' => $user->email,
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');

        return response()->json([
            'status' => true,
            'token' => $jwt,
            'message' => 'Login successful',
        ], 200);
    }

    // Register Method
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).+$/',
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        // Create the user
        $user = DB::table('users')->insert([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number
        ]);    

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    public function get(){
        print_r("hello");
    }
}
