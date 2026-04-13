<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movetoinventorylines extends Model
{
    protected $table = 'p_qc_lines_t';
	protected $primaryKey = 'qc_line_id';
	protected $filable=['qc_header_id','lineno','product_id','uom_code_id','qty','receive_qty','accept_qty','reject_qty','reason'];
}
