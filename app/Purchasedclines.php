<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchasedclines extends Model
{
       protected $table="p_dc_lines_t";
    protected $primaryKey="dc_line_id";
    public $foreignKey='dc_hdr_id';
	protected $fillable = [
        'dc_line_id','dc_hdr_id', 'line_no','product_id','uom_code_id','qty','comments'
    ];
}
