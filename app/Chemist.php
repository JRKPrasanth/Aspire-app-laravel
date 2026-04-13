<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chemist extends Model
{
    protected $table ='app_chemist_t';
	protected $primaryKey ='chemist_id';
	protected $fillable=['chemist_name','chemist_address','doctor_id','chemist_lat','chemist_lon','stockist_id','created_at','updated_at','last_updated_by','location_id','organization_id','company_id','created_by','group_of_trade','product_id','chemist_type'];

	public function getTableColumns() 
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}