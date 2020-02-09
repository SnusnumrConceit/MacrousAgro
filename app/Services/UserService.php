<?php
declare(strict_types=1);


namespace App\Services;


use App\Exports\UsersExport;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\User;
use App\Http\Resources\User\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class UserService
{
    public function index(Request $request)
    {
        try {
            $users = User::query();

            $users->when(! empty($request->keyword), function ($q) use ($request) {
               return $q->where('last_name', 'LIKE', '%' . $request->keyword . '%')
                   ->orWhere('first_name', 'LIKE', '%' . $request->keyword . '%');
            });

            $users->when(! empty($request->birthday), function ($q) use ($request) {
                return $q->whereBirthday($request->birthday);
            });

            $users = $users->paginate(15);

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
            /** Validate */
//            $user = User::create([
//                'password' => bcrypt($request->password),
//                'email' => $request->email,
//                'first_name' => $request->first_name,
//                'last_name' => $request->last_name,
//                'birthday' => $request->birthday
//            ]);

            return response()->json([
                'status' => 'success',
                'msg' => __('users_form_msg_success_create')
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
                'msg' => __('users_form_msg_success_update')
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


    public function export(Request $request)
    {
//        return Excel::download(new UsersExport, 'users.xlsx');
        return new UsersExport();
    }
}
