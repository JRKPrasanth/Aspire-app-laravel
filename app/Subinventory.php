<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subinventory extends Model
{
    protected $table="m_subinventory_t";
	protected $primaryKey="subinventory_id";
	public $foreignKey='';	
	protected $fillable=['subinventory_name','description','active','organization_id','company_id','created_at','updated_at','status'];	
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
