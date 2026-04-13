<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchaseqc extends Model
{
 protected $table = 'p_qc_header_t';
 protected $primaryKey ='qc_header_id';
 protected $fillable =['qc_header_id','po_number','po_date','supplier_id','grn_number','bill_number',"description","qc_number",'source_type','start_date','end_date','batch_no','employee_id'];

	
}
