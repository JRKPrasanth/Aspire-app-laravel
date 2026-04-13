<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchasereturn extends Model
{
      protected $table="p_return_header_t";
    protected $primaryKey="return_header_id";
	protected $fillable = [
        'return_header_id','po_number','po_date', 'supplier_id','return_date','grn_number','bill_number'
    ];
}
