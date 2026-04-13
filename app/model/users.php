<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'tb_users';

    protected $fillable = [
        'username', 'email', 'password', 'company_id'
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthIdentifierName()
    {
        return 'username';
    }
}

