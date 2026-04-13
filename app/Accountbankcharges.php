<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountbankcharges extends Model
{
     protected $table='f_account_bankcharges_t';
      protected $primaryKey='account_bankcharge_id';
      protected $fillable =['bank_id','payment_type_id','from_value','to_value','bank_charges','active'];
}
