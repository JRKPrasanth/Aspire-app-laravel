<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assetcategory extends Model
{
    protected $table='f_asset_category_t';
      protected $primaryKey='asset_category_id';
      protected $fillable =['asset_category_name','description','active','asset_type_id'];
}
