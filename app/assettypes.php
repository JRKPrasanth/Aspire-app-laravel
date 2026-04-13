<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class assettypes extends Model
{
     protected $table='f_asset_types_t';
      protected $primaryKey='asset_type_id';
      protected $fillable =['asset_type_name','description','active'];
}
