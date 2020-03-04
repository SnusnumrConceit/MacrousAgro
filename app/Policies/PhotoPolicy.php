<?php

namespace App\Policies;

use App\Models\Photo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhotoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any photos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_photos');
    }

    /**
     * Determine whether the user can view the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Photo  $photo
     * @return mixed
     */
    public function view(User $user, Photo $photo)
    {
        return $user->hasPermission('view_photos');
    }

    /**
     * Determine whether the user can create photos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_photos');
    }

    /**
     * Determine whether the user can update the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Photo  $photo
     * @return mixed
     */
    public function update(User $user, Photo $photo)
    {
        return $user->hasPermission('update_photos');
    }

    /**
     * Determine whether the user can delete the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Photo  $photo
     * @return mixed
     */
    public function delete(User $user, Photo $photo)
    {
        return $user->hasPermission('delete_photos');
    }

    /**
     * Determine whether the user can restore the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Photo  $photo
     * @return mixed
     */
    public function restore(User $user, Photo $photo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Photo  $photo
     * @return mixed
     */
    public function forceDelete(User $user, Photo $photo)
    {
        //
    }
}
