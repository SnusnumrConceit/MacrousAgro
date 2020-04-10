<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Traits\HasRole;
use Laravel\Cashier\Billable;
use Route;

class User extends Authenticatable
{
    use HasRole, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'first_name',
        'last_name', 'birthday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'full_name', 'display_birthday',
        'registration_date', 'last_activity_date',
        'role'
    ];

    protected $perPage = 15;

    protected $dates = [
      'created_at', 'updated_at', 'birthday'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function getRoleAttribute()
    {
        return $this->roles()->exists() ? $this->roles()->first()->name : null;
    }

    public function getFullNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    public function getDisplayBirthdayAttribute()
    {
        return $this->birthday->format('d.m.Y');
    }

    public function getRegistrationDateAttribute()
    {
        return $this->created_at->format('d.m.Y H:i:s');
    }

    public function getLastActivityDateAttribute()
    {
        return $this->updated_at->format('d.m.Y H:i:s');
    }

    public static function apiRoutes()
    {
        Route::group(['prefix' => 'users'], function () {
            Route::get('search', 'UserController@index');
            Route::get('customers', function () {
                return self::all();
            });
        });
    }
}
