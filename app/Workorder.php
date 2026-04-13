<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workorder extends Model
{
    protected $table='w_workorder_hdr_t';
    protected $primaryKey='workorder_hdr_id';
	protected $guarded = [];   
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
