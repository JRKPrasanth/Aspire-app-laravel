<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class soinquirylines extends Model
{
    public $table='s_inquiry_lines_t';
	public $primaryKey='so_inquiry_lines_id';
	public $foreignKey='so_inquiry_hdr_id';
	public $fillable =['so_inquiry_lines_id','so_inquiry_hdr_id','product_id','uom_code_id','required_qty','need_by_date'];
}
