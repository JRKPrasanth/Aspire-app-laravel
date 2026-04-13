<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class qasubmitstage extends Model
{
    protected $table='w_qa_submitstage_trx_t';
    protected $primaryKey='qa_submitstage_trx_hdr_id';
    protected $fillable=['job_no','batch_no','remarks','verifier','jobcard_qty','job_date','start_date','end_date','organization_id','job_assigned_to','total_working_hrs','actual_hrs','working_hrs','from_time','to_time','manufacturer_date','product_expire_date','machine_assigned_to','machine_actual_hrs'.'machine_start_time','machine_end_time','machine_working_hrs','machine_qty'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
