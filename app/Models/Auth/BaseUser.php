<?php

namespace App\Models\Auth;

use Altek\Accountant\Contracts\Recordable;
use Altek\Accountant\Recordable as RecordableTrait;
use Altek\Eventually\Eventually;
use App\Models\Auth\Traits\SendUserPasswordReset;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Airlock\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User.
 */
abstract class BaseUser extends Authenticatable implements Recordable
{
    use HasApiTokens,
        HasRoles,
        Eventually,
        Notifiable,
        RecordableTrait,
        SendUserPasswordReset,
        SoftDeletes,
        Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'email_verified_at',
        'password',
        'password_changed_at',
        'mobile',
        'mobile_verified_at',
        'avatar',
        'active',
        'last_login_at',
        'last_login_ip',
        'to_be_logged_out',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_login_at',
        'password_changed_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
