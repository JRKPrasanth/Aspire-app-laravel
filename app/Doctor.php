<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table ='app_doctors_t';
	protected $primaryKey ='doctor_id';
	protected $fillable=['doctor_name','doctor_address','doctor_phone_number','doctor_email_id','gender','marital_status','area','grade','degree','specialization','doa','dob','state_group','focus_product','remarks','other','latitude','longtitude','location_address','from_time','to_time','days','status','created_at','created_by','updated_at','last_updated_by','location_id','organization_id','company_id','parent_id','active','doctor_type','last_name','chemist_id','stockist_id','association'];

	public function getTableColumns() 
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}