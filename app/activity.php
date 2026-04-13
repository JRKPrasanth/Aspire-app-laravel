<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activity extends Model
{
    	protected $table = 'm_activity_t';
		protected $primaryKey = 'activity_id';
	protected $fillable =['activity_name','description','company_id','location_id','organization_id	','active'];

}