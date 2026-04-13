<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobworkoutorderlines extends Model
{
    public $table='w_jobworkoutorder_lines_t';
	public $primaryKey='jobworkoutorder_line_id';
	public $foreignKey='jobworkoutorder_hdr_id';
	public $fillable =['jobworkoutorder_hdr_id','line_no','product_id','qty','comments','uom_code_id','need_by_date'];
}

