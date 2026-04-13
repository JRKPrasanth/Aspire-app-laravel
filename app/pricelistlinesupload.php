<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pricelistlinesupload extends Model
{
    public $table='i_pricelist_lines_t';
	public $primaryKey='pricelist_line_id';
	public $foreignKey='pricelist_hdr_id';
	public $fillable =['pricelist_hdr_id','line_no','product_id','unit_price','std_price','start_date','end_date'];
}
