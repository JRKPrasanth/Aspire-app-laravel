<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountcurrency extends Model
{
   protected $table='f_account_currency_t';
      protected $primaryKey='account_currency_id';
 protected $fillable =['currency_code','start_date','end_date','active','created_by','last_updated_by'];
}
