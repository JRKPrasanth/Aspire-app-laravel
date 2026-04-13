<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class arfreightcarrierslines extends Model
{
    protected $table='m_frieghtcarriers_lines_t';
    protected $primaryKey='freightcarriers_lines_id';
    protected $foreignKey='ar_frieghtcarriers_hdr_id';
    protected $fillable=['ar_frieghtcarriers_hdr_id','carrier_number','carrier_registration','permit_number','carrier_address','country'
  ,'state','city','active','comments','carrier_name'];
}
