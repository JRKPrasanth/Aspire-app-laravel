<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class documentinterntransfer extends Model
{
    protected $table='a_doctransfer_hdr_t';
    protected $primaryKey='doc_hdr_id';
    protected $fillable=['emp_id','doc_given_date','doc_receive_date','doc_receive_by','status','remarks','created_at','created_by'
						,'updated_at','last_updated_by','company_id','location_id','organization_id'];
						public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

