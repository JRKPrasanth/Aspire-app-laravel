<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workorderlines extends Model
{
    public $table='w_workorder_lines_t';
	public $primaryKey='workorder_line_id';
	public $foreignKey='workorder_hdr_id';
	public $fillable =['workorder_hdr_id','line_no','product_id','qty','comments','uom_code_id','due_date'];
}

