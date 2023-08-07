<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    use ApiResponse;
    public function register(RegisterRequest $request)
    {
        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);  
            return $this->successResponse($user,'User Registered Successfully.',201);
        }
        catch(Exception $e){
            return $this->errorResponse('Error. '. $e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if(!Auth::attempt($credentials))
        {
            return $this->errorResponse('Error. Invalid Email or Password',401);
        }
        $user = User::where('email',$request->email)->first();
        $token = $user->createToken("User Token")->plainTextToken;
        return $this->successResponse($token, "User Logedin Successfully.", 200);
    }

    public function logout()
    {
        User::findOrFail(Auth::id())->tokens()->delete();
        auth()->logout();
        return $this->successResponse(null,"User Logedout Successfully.",200);
    }

    public function forgotPassword()
    {
        
    }

    public function sendResetLink()
    {
        
    }
}
