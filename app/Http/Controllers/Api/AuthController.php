<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
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

        auth()->guard()->login($user);

        return response()->json([
            'user' => $user,
            'msg' => 'Пользователь успешно зарегистрирован'
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
            throw new \Exception('Неверные данные', 500);
        }

        return response()->json([
            'msg' => 'success'
        ], 200);
    }

    /**
     * Выход пользователя из системы
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'msg' => 'success'
        ], 200);
    }

//    /**
//     * Получение зарегистрированного пользователя
//     *
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function user()
//    {
//        return response()->json([
//            'user' => auth()->user(),
//            'msg' => 'success'
//        ]);
//    }
}
