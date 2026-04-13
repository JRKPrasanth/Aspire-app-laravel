<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receiptforinvoice extends Model
{
    protected $table='s_receipts_t';
      protected $primaryKey='receipt_id';
      protected $fillable =['receipt_id','receipt_number','invoice_hdr_id','customer_id','receipt_date','receipt_amount','account_code_id','receipt_type_id','cheque_no','account_no','bank_id','remarks','receipt_reference','bank_name','account_number','ifsc_code','reference_no','check_date'];
}
