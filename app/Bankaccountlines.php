<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bankaccountlines extends Model
{
   public $table='f_bank_account_lines_t';
	public $primaryKey='bank_account_line_id';
	public $foreignKey='bank_account_hdr_id';
	public $fillable =['bank_account_line_id','bank_account_hdr_id','branch_name','branch_address','ifsc_code',
            'account_type','name_in_account','nickname_in_acoount','account_number','start_date','end_date','active','comments','account_code_id'];
}
