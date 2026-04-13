<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tdsslab extends Model
{
    protected $table='f_tds_slab_t';
      protected $primaryKey='tds_slab_id';
      protected $fillable =['tds_percentage','tds_type','start_date','end_date','active'];
}
