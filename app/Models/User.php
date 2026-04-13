<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; 

class User extends Authenticatable
{
    use Notifiable;   

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


