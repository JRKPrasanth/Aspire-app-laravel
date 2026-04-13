<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class investmenttype extends Model
{
     protected $table='hr_inve_type';
    protected $primaryKey='inv_id';
    protected $fillable = ['inv_name','inv_limit','inv_id'];
}
