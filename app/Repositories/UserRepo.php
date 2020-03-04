<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Exports\UsersExport;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\User;
use App\Http\Resources\User\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class UserRepo
{
    public function index(Request $request)
    {
        try {
            $users = User::query();

            $users->when($request->keyword, function ($q, $keyword) {
                return $q->where('last_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });

            $users->when($request->updated_at, function ($q, $updated_at) {
                return $q->whereBetween('updated_at', [$updated_at . ' 00:00', $updated_at . ' 23:59']);
            });

            $users = $users->with('roles')->latest('created_at')->paginate(15);

            return response()->json([
                'users' => $users
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        try {

            $data = $request->validated();

            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);

            return response()->json([
                'status' => 'success',
                'msg' => __('user_msg_success_create')
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return response()->json([
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            $user->update($data);
//            $user->update([
//                'email' => $request->email,
//                'first_name' => $request->first_name,
//                'last_name' => $request->last_name,
//                'birthday' => $request->birthday
//            ]);

            return response()->json([
                'status' => 'success',
                'msg' => __('user_msg_success_update')
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success',
            'msg' => __('user_msg_success_delete')
        ], 200);
    }
}