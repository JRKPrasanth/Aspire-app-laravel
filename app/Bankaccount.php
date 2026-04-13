<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bankaccount extends Model
{
   protected $table='f_bank_account_hdr_t';
      protected $primaryKey='bank_account_hdr_id';
      protected $fillable =['bank_name','bank_type','active'];
}
