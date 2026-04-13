<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journaladjustments extends Model
{
    protected $table='f_adjustments_t';
      protected $primaryKey='adjustment_id';
      protected $fillable =['adjustment_date','account_type','adjustment_amount','description','reason_code','account_code_id'];

}
