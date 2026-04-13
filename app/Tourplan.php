<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tourplan extends Model
{
    protected $table ='app_tourprogram_t';
	protected $primaryKey ='tourprogram_id';
	protected $fillable=['employee_id','tour_date','tour_details','tour_area','remarks','organization_id','company_id','location_id'];
}
