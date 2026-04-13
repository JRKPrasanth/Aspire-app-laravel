<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchasereplacement extends Model
{
    protected $table="p_replacement_hdr_t";
    protected $primaryKey="replacement_hdr_id";
	protected $fillable = [
        'replacement_hdr_id','replacement_no','po_number', 'replacement_date','grn_number','bill_number'
    ];
}
