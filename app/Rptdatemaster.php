<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rptdatemaster extends Model
{
    protected $table='m_rpt_date_tbl';
      protected $primaryKey='id';
      protected $fillable =['date','comments','created_by','last_updated_by','company_id','location_id','organization_id'];
}
