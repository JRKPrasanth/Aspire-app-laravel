<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chemistdcr extends Model
{
    protected $table ='app_chemist_dcr_t';
	protected $primaryKey ='chemist_dcr_id';
	protected $fillable=['tp_date','from_area','to_area','chemist_name','order_no','value','remarks','created_by','created_at','last_updated_by','updated_at','organization_id','location_id','company_id'];
}
