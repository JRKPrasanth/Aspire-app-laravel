<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tcsslab extends Model
{
    protected $table='f_tcs_slab_t';
      protected $primaryKey='tcs_slab_id';
      protected $fillable =['tcs_percentage','tcs_type','start_date','end_date','active'];
}
