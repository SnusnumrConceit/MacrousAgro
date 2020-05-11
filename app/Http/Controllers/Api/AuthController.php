<?php

namespace App\Http\Controllers\Api;

//use App\Exceptions\ApiErrorMessageException;
use App\User;
use App\Events\User\Registered;
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

        event(new Registered($user));

        auth()->guard()->login($user);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken(md5(now()))->plainTextToken,
            'message' => 'Пользователь успешно зарегистрирован'
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
            return response()->json(['message' => 'Неверные данные'], 500);
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

        auth()->logout();

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
