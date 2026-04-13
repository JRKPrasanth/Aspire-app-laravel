<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockistdcr extends Model
{
    protected $table ='app_stockist_dcr_t';
	protected $primaryKey ='stockist_dcr_id';
	protected $fillable=['tp_date','from_area','to_area','stockist_name','order_no','value','remarks','created_by','created_at','last_updated_by','updated_at','organization_id','location_id','company_id'];
}
