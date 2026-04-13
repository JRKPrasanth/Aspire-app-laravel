<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class receiptbatcheshdr extends Model
{
    protected $table='s_receipt_batches_hdr_t';
    protected $primaryKey='s_receipt_batch_hdr_id';
	public $foreignKey='s_receipt_batch_hdr_id';
	protected $fillable =['s_receipt_batch_hdr_id','	receipt_batch_name','customer_id','customer_site_id','receipt_batch_status','receipt_batch_status','receipt_batch_status'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
