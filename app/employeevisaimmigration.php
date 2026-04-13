<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeevisaimmigration extends Model
{
   protected $table='hr_emp_visa_immigration';
   protected $primaryKey='id';
    protected $fillable=['employee_id','passport_number','passport_issued_date','passport_expiry_date','visa_country',
        'visa_type_code','visa_number','visa_issued_date','visa_expiry_date','files'];
}
