<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receiptdetails extends Model
{
      protected $table='s_receipts_t';
      protected $primaryKey='receipt_id';
      protected $fillable =['receipt_id','receipt_number','receipt_date','receipt_amount','account_code_id','receipt_type_id','cheque_no','account_no','bank_id','remarks','receipt_reference','bank_name','account_number','reference_no','check_date','customer_account_name'];
}
