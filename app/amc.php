<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class amc extends Model
{
   protected $table='m_amc_tb';
    protected $primaryKey='vendor_id';
    protected $fillable=['vendor_name','address','mobileno','mail'];
}
