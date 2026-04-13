<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paymentbatcheslines extends Model
{
    protected $table = 'p_payment_batches_lines_t';
        protected $primaryKey ='payment_batches_line_id';
        public $foreignKey='payment_batches_hdr_id';
        protected $fillable =['line_no','po_invoice_id','invoice_amount','invoice_due_date','comments'];
}
