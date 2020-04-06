<?php

namespace App\Http\Controllers;

use App\User;
use App\Services\UserService;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\User as UserResource;

class UserController extends Controller
{
    public $service, $user;

    public function __construct(UserService $userService, UserRepo $user)
    {
        $this->user = $user;
        $this->service = $userService;
//        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function index(Request $request)
    {
        $users = $this->user->index($request);

        return response()->json([
            'users' => $users
        ], 200);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function store(UserStoreRequest $request)
    {
       $this->user->store($request->validated());

        return response()->json([
            'status' => 'success',
            'msg' => __('user_msg_success_create')
        ]);

    }

    /**
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
        $this->user->update($request->validated(), $user);

        return response()->json([
            'status' => 'success',
            'msg' => __('user_msg_success_update')
        ], 200);
    }

    /**
     * Remove the user from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function destroy(User $user)
    {
        $this->user->destroy($user);

        return response()->json([
            'status' => 'success',
            'msg' => __('user_msg_success_delete')
        ], 200);
    }

    public function export(Request $request)
    {
        return ;
    }
}
