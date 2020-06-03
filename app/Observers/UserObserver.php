<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->password = bcrypt($user->password);
    }

    /**
     * Handle the user "updating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if (request()->has('password')) {
            $user->password = bcrypt(request()->input('password'));
        }
    }

    public function deleting(User $user)
    {
        $user->orders()->delete();
    }
}
