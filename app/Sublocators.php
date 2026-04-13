<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sublocators extends Model
{
    protected $table="m_sublocators_t";
	protected $primaryKey="sublocator_id";
	public $foreignKey='subinventory_id';
	protected $fillable=['subinventory_id','row_no','rack_no','bin_no','locator_code','locator_name'];	
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
