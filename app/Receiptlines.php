<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receiptlines extends Model
{
    protected $table = 's_receipts_lines_t';
    protected $primaryKey ='receipt_line_id';
    public $foreignKey='receipt_hdr_id';
    protected $fillable =['invoice_hdr_id','receipt_amount','paid_amount','balance_amount','account_structure_id','batch_invoice_amount','account_structure_id','comments'];

}
