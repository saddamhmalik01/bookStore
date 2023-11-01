<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $role = $user->is_admin ? 'admin' : 'user';
            return successResponse('Login success',['user'=> $user,'token'=> $token,'role'=>$role]);
        }
        return errorResponse('Invalid credentials');
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 0,
        ]);
       return $user ?
           successResponse('Registration successful',
               [
                   'user'=> $user ,
                   'token'=> $user->createToken('token')->plainTextToken,
                   'role'=>'user'
               ])
           : errorResponse('Something went wrong! please try again');
    }
}
