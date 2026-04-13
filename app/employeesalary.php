<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeesalary extends Model
{
    protected $table='hr_emp_salary';
     protected $primaryKey='id';
    protected $fillable=['employee_id','bank_name','branch_name','ifsc_code','account_holder_name','account_type','account_number',
                    'default_acc','files'];
}
