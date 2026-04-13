<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

    class Batchconversion extends Model
    {
          protected $table='i_batch_conversion_t';
          protected $primaryKey='id';
          protected $fillable =['bc_number','type','convert_date','approver_id','from_product','product_id','to_product','from_batch','from_subinventory','active','from_locator','qty','from_qty','to_qty','qoh','to_batch','to_subinventory','to_locator','status','created_by','created_at','last_updated_by','updated_at','company_id'];
    }
