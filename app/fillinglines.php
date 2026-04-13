<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fillinglines extends Model
{
	protected $table='w_fillings_lines_t';
  	protected $primaryKey='fillings_lines_id';
  	public $foreignKey='fillings_hdr_id';
	protected $fillable = ['line_no','product_id','uom_code_id', 'comments','reference_source','qty','reference_hdr_id','reference_line_id','created_by','last_updated_by','created_at','updated_at','organization_id','company_id','location_id'];
}
