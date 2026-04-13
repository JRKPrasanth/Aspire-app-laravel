<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class msalesreceiptlines extends Model
{
   protected $table='s_m_receipts_lines_t';
    protected $primaryKey='receipt_line_id';
    public $foreignKey='receipt_id';
	protected  $fillable =['receipt_line_id','receipt_hdr_id','line_no','invoice_hdr_id','receipt_amount','balance_amount','invoice_amount','expense_amount'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}