<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soquotelines extends Model
{
  public $table='s_quote_lines_t';
	public $primaryKey='quote_line_id';
	public $foreignKey='quote_hdr_id';
	public $fillable =['quote_line_id','quote_hdr_id','product_id','uom_code_id','qty',
            'unit_price','discount_percentage','discount_amount','tax_group_id','tax_amount','line_total','line_subtotal','promised_date'];

}
