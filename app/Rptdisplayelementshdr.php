<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rptdisplayelementshdr extends Model
{
    protected $table='a_rpt_displayelements_hdr_t';
    protected $primaryKey='rpt_displayelements_hdr_id';
    protected $fillable=['report_source','set_name','organization_id','start_date','end_date'];
}
