<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchaseqclines extends Model
{
   protected $table = 'p_qc_lines_t';
   public $primaryKey='qc_line_id';
public $foreignKey='qc_header_id';
 public $fillable =['qc_line_id','qc_header_id','product_id','uom_code_id','qty',
            'receive_qty','accept_qty','reject_qty','reason',"line_no","po_hdr_id"];

	
}
