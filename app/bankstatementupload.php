<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bankstatementupload extends Model
{
    protected $table = 'f_bankstmtupload_t';
   	protected $primaryKey ='bankstmt_id';
   	protected $fillable =['bank_name','account_no','batch_name','batch_status','batch_date','batch_comments','date','mode','particulars','deposits','withdrawals','company_id','organization_id','location_id','created_by','created_at','last_updated_by','updated_at','balance'];
}
