<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $table='f_expenses_t';
      protected $primaryKey='expense_id';
      protected $fillable =['source','expense_id','expense_date','expense_type','gst_code_id','expense_amount','expense_account_id','supplier_id','reverse_charge','invoice','remarks','tax_group_id','customer_id','round_off'];
}
