<?php

namespace App;

use App\Models\Order;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Traits\HasRole;
// use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Route;

class User extends Authenticatable
{
    use HasRole, Notifiable, HasApiTokens;

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

    /**
     * Роли пользователя
     *
     * Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Заказы пользователя
     *
     * Orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Роль пользователя
     *
     * Get user role
     *
     * @return null
     */
    public function getRoleAttribute()
    {
        return $this->roles()->exists() ? $this->roles()->first()->name : null;
    }

    /**
     * Полное имя пользователя
     *
     * Get full name
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    /**
     * Отформатированная дата рождения
     *
     * Get formatted birthday date
     *
     * @return mixed
     */
    public function getDisplayBirthdayAttribute()
    {
        return $this->birthday->format('d.m.Y');
    }

    /**
     * Отформатированная дата регистрации
     *
     * Get formatted registration date
     *
     * @return mixed
     */
    public function getRegistrationDateAttribute()
    {
        return $this->created_at->format('d.m.Y H:i:s');
    }

    /**
     * Отформатированная дата последней активности
     *
     * Get formatted last activity date
     *
     * @return mixed
     */
    public function getLastActivityDateAttribute()
    {
        return $this->updated_at->format('d.m.Y H:i:s');
    }

    /**
     * Получить список администраторов
     *
     * Get listing of the admin users
     *
     * @return mixed
     */
    public function scopeAdmins()
    {
        return User::whereHas('roles', function($q) {
            return $q->whereName('administrator');
        })->get();
    }

    /**
     * API-маршруты
     *
     * API-routes
     */
    public static function apiRoutes()
    {
        Route::group(['prefix' => 'users'], function () {
            Route::post('export', 'UserController@export')->name('users.export');
            Route::get('search', 'UserController@index');
            Route::get('customers', function () {
                return self::all();
            });
        });
    }
}
