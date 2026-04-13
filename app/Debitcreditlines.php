<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debitcreditlines extends Model{
   protected $table='f_debitcredit_lines_t';
   protected $primaryKey ='debitcredit_line_id';
   public $foreignKey='debitcredit_id';
   protected $fillable =['debitcredit_id','debitcredit_line_id','debitcredit_account_id','tax_group_id','tax_amount','debitcredit_line_amount','remarks'];
}
