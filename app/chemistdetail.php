<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chemistdetail extends Model
{
    protected $table ='app_chemist_detail_t';
    protected $foreignKey='chemist_id';
	protected $primaryKey ='chemist_detail_id';
	protected $fillable=['doctor_id','chemist_address','area','country_id','state_id','city_id','created_at','updated_at','last_updated_by','location_id','organization_id','company_id','created_by','concat_address','pincode'];
}
