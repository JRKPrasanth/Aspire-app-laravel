<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movetoinventory extends Model
{
    protected $table ='p_qc_header_t';
	protected $primaryKey ='qc_header_id';
	protected $fillable =['qc_number','po_number','po_date','supplier_id','qc_date','grn_number','bill_number'];
}
