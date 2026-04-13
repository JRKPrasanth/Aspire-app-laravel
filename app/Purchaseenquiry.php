<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseenquiry extends Model
{
   protected $table='p_enquiry_hdr_t';
    protected $primaryKey='enquiry_hdr_id';
    public $foreignKey='';
	protected $fillable =['enquiry_hdr_id','enquiry_number','enquiry_date','enquiry_type_id','supplier_id','suppliersite_id','project_id','remarks'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
