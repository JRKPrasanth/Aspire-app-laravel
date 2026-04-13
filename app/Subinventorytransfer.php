<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subinventorytransfer extends Model
{
    protected  $table = 'i_subinventory_transfer_hdr_t';
	protected $primaryKey ='subinventory_transfer_hdr_id';
	protected $fillable =['trx_date','frm_subinv_id','frm_loc_id','to_subinv_id','to_loc_id']; 
}
