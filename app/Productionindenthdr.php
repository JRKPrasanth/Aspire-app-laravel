<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productionindenthdr extends Model
{
    protected $table='w_requisition_indent_hdr_t';
    protected $primaryKey='w_requisition_indent_hdr_id';
	protected $fillable =['w_requisition_indent_hdr_id','indent_no','indent_date','indent_status','requestor_id','project_id','organization_id','remarks'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
