<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productionplanlines extends Model
{
    public $table='w_productionplan_lines_t';
	public $primaryKey='productionplan_line_id';
	public $foreignKey='productionplan_hdr_id';
	public $fillable =['productionplan_hdr_id','line_no','product_id','qty','uom_code_id'];
}
