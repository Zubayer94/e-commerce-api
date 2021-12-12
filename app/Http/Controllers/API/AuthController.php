<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 404);
        }

        $userData = $request->only(['name', 'email']);
        $userData['password'] = bcrypt($request->password);
        try {
            $user = User::create($userData);
            return response()->json(['response' => 'Registered successfully', 'user' => $user],  200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * User login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 404);
        }
        try {
            if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                $accessToken = auth()->user()->createToken('authToken')->plainTextToken;
                return response(['user' => auth()->user(), 'token' => $accessToken], 200);
            } else {
                return response(['message' => 'Invalid credentials'], 404);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return response(['message' => $ex->getMessage()],  400);
        }
    }

    /**
     * Get auth user
     *
     * @return \Illuminate\Http\Response
     */
    public function getAuthUser()
    {
        return auth()->user();
    }

    /**
     * Logout auth user
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        // way 1
        auth()->user()->tokens()->delete();

        // way 2
        // auth()->user()->tokens->each(function ($token, $key) {
        //     $token->delete();
        // });

        return response()->json('Logged out successfully!', 200);
    }
}
