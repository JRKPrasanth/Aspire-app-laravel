<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empexpenses extends Model
{
    protected $table='f_emp_expenses_t';
      protected $primaryKey='expense_id';
      protected $fillable =['source','expense_id','expense_date','expense_amount','remarks','round_off'];
}
