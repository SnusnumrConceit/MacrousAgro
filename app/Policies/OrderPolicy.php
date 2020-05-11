<?php

namespace App\Policies;

use App\Models\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('orders_view');
    }

    /**
     * Determine whether the user can view the order.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        return $user->hasPermission('orders_view');
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('orders_create');
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        return $user->hasPermission('orders_update');
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        return $user->hasPermission('orders_delete');
    }

//    /**
//     * Determine whether the user can restore the order.
//     *
//     * @param  \App\User  $user
//     * @param  \App\Models\Order  $order
//     * @return mixed
//     */
//    public function restore(User $user, Order $order)
//    {
//        //
//    }

//    /**
//     * Determine whether the user can permanently delete the order.
//     *
//     * @param  \App\User  $user
//     * @param  \App\Models\Order  $order
//     * @return mixed
//     */
//    public function forceDelete(User $user, Order $order)
//    {
//        //
//    }
}
