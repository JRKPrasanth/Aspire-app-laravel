<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class targets extends Model
{
     protected $table="s_target_tbl";
	protected $primaryKey="targets_id";
	protected $fillable=['customer_id','month','year','value','created_by','company_id'];
}
