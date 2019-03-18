<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'firstname',
        'middlename',
        'lastname',
        'password',
        'actual_password',
        'user_type',
        'is_admin',
        'is_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'actual_password', 'remember_token',
    ];

    public function findForPassport($identifier)
    {
        return User::orWhere('username',$identifier)->where('is_deleted',0)->first();
    }
}