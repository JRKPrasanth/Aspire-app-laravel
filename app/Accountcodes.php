<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountcodes extends Model
{
   protected $table='f_account_codes_hdr_t';
      protected $primaryKey='account_codes_hdr_id';
      protected $fillable =['account_class_id','main_account_code','active'];
}
