<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeereporting extends Model
{
  
	public $table='hr_reporting_tbl';
	public $primaryKey='reporting_id';
	public $foreignKey='';	
	public $fillable =['reporting_id','reporting_manager','assing_employee','employees','created_at','updated_at','created_by','last_updated_by','company_id','location_id','organization_id'];
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

