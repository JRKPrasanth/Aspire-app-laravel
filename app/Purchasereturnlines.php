<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchasereturnlines extends Model
{
       protected $table="p_return_lines_t";
    protected $primaryKey="return_line_id";
    public $foreignKey='return_header_id';
	protected $fillable = [
        'return_line_id','item_name','return_header_id', 'line_no','product_id','uom_code_id','qty','receive_qty','reject_qty'
    ];
}
