<?php

namespace App\Http\Controllers;

use App\User;
use App\Services\UserService;
use App\Http\Resources\User\User as UserResource;

use Illuminate\Http\Request;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;

class UserController extends Controller
{
    public $userService, $user;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
//        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Список пользователей
     *
     * Display a listing of the users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function index(Request $request)
    {
        $users = User::query();

        $users->when($request->keyword, function ($q, $keyword) {
            return $q->where('last_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('first_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('email', 'LIKE', '%' . $keyword . '%');
        });

        $users->when($request->updated_at, function ($q, $updated_at) {
            return $q->whereBetween('updated_at', [$updated_at . ' 00:00:00', $updated_at . ' 23:59:59']);
        });

        $users = $users->with('roles')->latest('created_at')->paginate();

        return response()->json([
            'users' => $users
        ], 200);
    }

    /**
     * Сохранение пользователя
     *
     * Store a newly created user in storage.
     *
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function store(UserStoreRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('users.response.messages.created')
        ]);

    }

    /**
     * Информация о пользователе
     *
     * Display the specified user.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * Форма редактирования пользователя
     *
     * Show the form for editing the user.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(User $user)
    {
        return response()->json([
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * Update the user in storage.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('users.response.messages.updated')
        ], 200);
    }

    /**
     * Удаление пользователя
     *
     * Remove the user from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('users.response.messages.deleted')
        ], 200);
    }

    /**
     * Экспорт пользователей
     *
     * Export listing of the users
     *
     * @param Request $request
     * @return \App\Exports\UsersExport
     */
    public function export(Request $request)
    {
        return $this->userService->export();
    }
}
