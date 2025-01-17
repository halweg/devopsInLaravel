<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where('email', $request->email)->first();

            $token = $user->createToken('token', ['*'])->plainTextToken;

            return response([
                'data' => ['token' => $token],
            ]);
        }

        return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
