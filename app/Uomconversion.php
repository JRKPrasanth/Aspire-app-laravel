<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uomconversion extends Model
{
      protected $table = 'm_uom_conversion_t';
      protected $primaryKey='uom_conversion_id';
	  protected $fillable = ['primary_uom','trx_uom','uom_value','active'];
}
