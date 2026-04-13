<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialreceivelines extends Model
{
    protected $table="w_materialreceive_line_t";
	protected $primaryKey="w_materialreceive_line_id";
	public $foreignKey='w_materialreceive_hdr_id';
	protected $fillable=['w_materialreceive_line_id','w_materialreceive_hdr_id','product_id','uom_code','qty','qoh','comments'];
}
