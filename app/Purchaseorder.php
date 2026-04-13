<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseorder extends Model
{
 	  protected $table='p_po_hdr_t';
      protected $primaryKey='po_hdr_id';
	  protected $guarded = [];  
	
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
	
}
