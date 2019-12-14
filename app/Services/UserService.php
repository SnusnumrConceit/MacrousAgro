<?php
declare(strict_types=1);


namespace App\Services;


use App\Exports\UsersExport;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class UserService
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) : Response
    {
        try {
            /** Validate */
            $user = User::create([
                'password' => $request->password,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birthday' => $request->birthday
            ]);

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $users = User::paginate(15);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info(int $id)
    {
        try {
            /** Validate */
            $user = User::findOrFail($id);

            return response()->json([
                'user' => $user
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            /** Validate */
            $user = User::findOrFail($id);

            $user->update([
                'password' => $request->password,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birthday' => $request->birthday
            ]);

            return response()->json([
                'status' => 'success',
                'msg' => __('users_form_msg_success_update')
            ]);

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
    public function destroy(int $id)
    {
        try {
            /** Validate */
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json([
                'status' => 'success',
                'msg' => __('users_form_msg_success_delete')
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }


    public function export(Request $request)
    {
//        return Excel::download(new UsersExport, 'users.xlsx');
        return new UsersExport();
    }
}
