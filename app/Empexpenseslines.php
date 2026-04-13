<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empexpenseslines extends Model{
   protected $table='f_emp_expenses_lines_t';
   protected $primaryKey ='expense_line_id';
   public $foreignKey='expense_id';
   protected $fillable =['expense_id','expense_line_id','expense_account_id','tax_group_id','tax_amount','expense_line_amount','employee_id','bill_no','bill_date','remarks'];
}
