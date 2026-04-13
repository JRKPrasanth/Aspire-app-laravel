<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountexchangerates extends Model
{
     protected $table='f_account_exchangerates_t';
      protected $primaryKey='account_exchangerate_id';
      protected $fillable =['from_currency_id','to_currency_id','from_date','to_date','conversion_rate','active'];
}
