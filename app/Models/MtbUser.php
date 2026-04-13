<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class MtbUser extends Authenticatable implements JWTSubject
{
    protected $table = 'tb_users';
    protected $primaryKey = 'id'; // Explicitly define primary key
    public $timestamps = true; // Assuming you have created_at and updated_at

    protected $fillable = [
        'employee_number', 'password', 'company_id', // add other fields as needed
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Returns the primary key value (id)
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}