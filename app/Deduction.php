<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $table='m_emp_esi';
    protected $primaryKey='id';
    protected $fillable = ['components','limit','limitto', 'date','employeer_contribute','company_contribute','company_contribute1','employee_type'];
}
