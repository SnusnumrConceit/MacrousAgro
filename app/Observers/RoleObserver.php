<?php

namespace App\Observers;

use Kodeine\Acl\Models\Eloquent\Role;

class RoleObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        $role->permissions()->sync(request('permissions'));
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
//        $role->permissions()->sync(request('permissions'));
    }

    /**
     * Handle the user "deleting" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function deleting(Role $role)
    {
        $role->permissions()->detach();
    }
}
