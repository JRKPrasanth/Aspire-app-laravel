<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class receiptbatcheslines extends Model
{
    public $table='s_receipt_batches_lines_t';
	public $primaryKey='s_receipt_batch_lines_id';
	public $foreignKey='s_receipt_batch_hdr_id';
	public $fillable =['s_receipt_batch_lines_id','s_receipt_batch_hdr_id','invoice_hdr_id','invoice_amount','invoice_due_date','comments'];
}
