<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soorderlines extends Model
{
   	
	public $table='s_salesorder_lines_t';
	public $primaryKey='sales_line_id';
	public $foreignKey='sales_hdr_id';
	 protected $guarded = []; 


	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
