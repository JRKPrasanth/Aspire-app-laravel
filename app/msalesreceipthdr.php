<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class msalesreceipthdr extends Model
{
   protected $table='s_m_receipts_t';
    protected $primaryKey='receipt_id';
	protected  $fillable =['receipt_id','receipt_number','receipt_date','invoice_amount','receipt_amount','account_code_id','receipt_type_id','receipt_reference','remarks','cheque_no','account_no','bank_id','reference_no','bank_date'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}