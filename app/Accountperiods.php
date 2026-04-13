<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountperiods extends Model
{
     protected $table='f_account_periods_t';
      protected $primaryKey='account_period_id';
      protected $fillable =['account_period_id','current_trx_date','month','year','from_date','to_date','period_name','quarter_no','period_status','company_id'];

	
}
