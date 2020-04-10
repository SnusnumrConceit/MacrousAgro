<?php
declare(strict_types=1);

namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;

class UserRepo
{
    /**
     * Display a listing of the users.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
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

        return $users->with('roles')->latest('created_at')->paginate();
    }

    /**
     * Store a newly created user in storage.
     *
     * @param array $userData
     */
    public function store(array $userData) : void
    {
        $userData['password'] = bcrypt($userData['password']);

        $user = User::create($userData);
    }

    /**
     * Update the user in storage.
     *
     * @param array $userData
     * @param User $user
     */
    public function update(array $userData, User $user) : void
    {
        if (! empty($userData['password'])) {
            $userData['password'] = bcrypt($userData['password']);
        }

        $user->update($userData);
    }

    /**
     * Remove the user from storage.
     *
     * @param User $user
     * @throws \Exception
     */
    public function destroy(User $user) : void
    {
        $user->delete();
    }
}