<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class materialissueline extends Model
{
    protected $table="w_materialissue_line_t";
	protected $primaryKey="w_materialissue_line_id";
	public $foreignKey='w_materialissue_hdr_id';
	protected $fillable=['w_materialissue_line_id','w_materialissue_hdr_id','product_id','uom_code','qty','qoh','comments'];
}
