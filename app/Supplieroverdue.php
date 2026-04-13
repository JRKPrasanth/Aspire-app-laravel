<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplieroverdue extends Model
{
    protected $table='m_supplieroverdue_t';
    protected $primaryKey='supplieroverdue_id';
    protected $fillable=['supplieroverdue_id','supplier_id','before_day','before_dis','after_day','after_int','calculation_id'];
}
