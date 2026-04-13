<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaserequisitionlines extends Model
{
    public $table='p_requisition_lines_t';
	public $primaryKey='requisition_line_id';
	public $foreignKey='requisition_hdr_id';
	public $fillable =['requisition_line_id','requisition_hdr_id','product_id','uom_code_id','qty',
            'product_description','promised_date','comments'];
}
