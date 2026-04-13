<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class arfreightcarriershdr extends Model
{
    protected $table='m_frieghtcarriers_hdr_t';
    protected $primaryKey='ar_frieghtcarriers_hdr_id';
    protected $fillable=['carrier_name','remarks','organization_id','source_type_id','start_date','end_date'];
}
