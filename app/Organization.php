<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
     protected $table='m_organizations_t';
    protected $primaryKey='organization_id';
	protected $fillable =['organization_code','organization_name','organization_type','company_id','location_id','active'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
