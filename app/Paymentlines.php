<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paymentlines extends Model
{
   protected $table = 'p_payment_lines_t';
    protected $primaryKey ='payment_line_id';
    public $foreignKey='payment_hdr_id';
    protected $fillable =['po_invoice_id','paid_amount','balance_amount','account_structure_id','batch_invoice_amount','account_structure_id','comments'];
}
