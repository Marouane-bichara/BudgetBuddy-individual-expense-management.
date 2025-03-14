<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //


    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role
        ]);


        $respone = [];
        $response["token"] = $user->createToken('authToken')->plainTextToken;
        $response["name"] = $user->name; 
        $response["email"] = $user->email;

        return response()->json([
            'message' => 'Register successful',
            'data' => $response, 
        ]);
    }

    public function login(request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }


    $user = User::where('email' , $request->email)->first();

    if(!$user || !Hash::check($request->password, $user->password))
    {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    $response = [];
    $response["token"] = $user->createToken('authToken')->plainTextToken;
    $response["name"] = $user->name;
    $response["email"] = $user->email;

    return response()->json([
        'message' => 'Login successful',
        'data' => $response,
    ]);
        
    }

    public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json([
        'message' => 'Logged out successfully'
    ]);
}
}
