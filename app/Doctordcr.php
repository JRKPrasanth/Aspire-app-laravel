<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctordcr extends Model
{
    protected $table ='app_doctor_dcr_t';
	protected $primaryKey ='doctor_dcr_id';
	protected $fillable=['tp_date','divert_detail','from_area','tp_deviation','tp_deviation_reason','to_area','doctor_id','focus_product','pricelist_id','other_product','visit_with','dcr_reminder','timing','remarks','activity_id','outcome_id','created_by','created_at','last_updated_by','updated_at','organization_id','location_id','company_id'];
}
