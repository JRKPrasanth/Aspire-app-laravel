<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soorder extends Model
{
    //
	public $table='s_salesorder_hdr_t';
	public $primaryKey='sales_hdr_id';
	public $foreignKey='';	
	protected $guarded = [];
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

