<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class monthendfreeze extends Model
{
      protected $table='a_monthendfreeze_t';
      protected $primaryKey='monthend_id';
      protected $fillable =['monthend_date','mindate1','maxdate1','active'];
}
