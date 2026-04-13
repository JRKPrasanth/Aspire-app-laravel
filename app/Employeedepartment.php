<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeedepartment extends Model
{
    protected $table='m_department_t';
    protected $primaryKey='department_id';
    protected $fillable=['department_id','department_code','department_name','description','active'];
}
