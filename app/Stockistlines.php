<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stockistlines extends Model
{
    protected $table ='app_stockist_lines_t';
    protected $primaryKey ='stockist_lines_id';
    protected $foreignKey='stockist_id';
	protected $fillable=['doctor_id','address','country_id','state_id','city_id','area','pincode','created_at','updated_at','last_updated_by','location_id','organization_id','company_id','created_by','active'];

	public function getTableColumns() 
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}