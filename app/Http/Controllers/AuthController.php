<?php

namespace App\Http\Controllers;

use App\User;
use App\Events\User\Registered;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Регистрация пользователя
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $user->assignRole('customer');

        event(new Registered($user));

        auth()->guard()->login($user);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken(md5(now()))->plainTextToken,
            'message' => __('registered')
        ], 200);
    }

    /**
     * Авторизация пользователя
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (! auth()->attempt($credentials)) {
            return response()->json(['message' => __('invalid_data')], 500);
        }

        return response()->json([
            'token' => auth()->user()->createToken(md5(now()))->plainTextToken
        ], 200);
    }

    /**
     * Выход пользователя из системы
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (auth()->user()) {
            auth()->user()->tokens()->delete();
        }

        auth()->guard('web')->logout();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    /**
     * Получение зарегистрированного пользователя
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json([
            'user' => auth()->user(),
            'tokens' => auth()->user()->tokens,
            'message' => 'success'
        ]);
    }
}
