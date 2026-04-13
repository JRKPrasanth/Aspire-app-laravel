<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debitcredit extends Model
{
    protected $table='f_debitcredit_t';
      protected $primaryKey='debitcredit_id';
      protected $fillable =['source','source_type','debitcredit_id','debitcredit_date','debitcredit_type','gst_code_id','debitcredit_amount','debitcredit_account_id','supplier_id','invoice','remarks','tax_group_id','customer_id','debitcredit_status'];
}
