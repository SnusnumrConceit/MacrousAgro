<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = ['email' => $request->email, 'password' => $request->password];

        $user = User::where($credentials)
            ->first();

        $this->validateAuthentication($credentials, $user->password);

        return $this->token($user);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        $credentials = ['email' => $request->email, 'password' => $request->password];

        $this->validateAuthentication($credentials, $user->password);

        return $this->token($user);
    }

    public function logout()
    {
        auth()->user()->tokens->each()->delete();
        auth()->logout();

        return response()->json([
            'msg' => 'Вы успешно вышли из аккаунта'
        ], 200);
    }

    public function refresh()
    {

    }

    public function user()
    {

        return response()->json([
            'user' => new User(User::find(auth()->user()->id))
        ], 200);
    }

    private function token(User $user)
    {
        $token = $user->createToken(Str::random(15))->plainText;

        return response()->json([
            'token' => $token
        ], 200)->header('Authentication', $token);
    }

    private function validateAuthentication(array $credentials, string $userPassword) : void
    {
        $isAuth = auth()->attempt($credentials);

        // TODO - проверить с отсутствующим пользователем в системе
        if (! $isAuth && Hash::check($credentials['password'], $userPassword)) {
            throw new \Exception('Неверный логин или пароль');
        }
    }
}
