<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class outcome extends Model
{
    protected $table ='m_outcome_t';
	protected $primaryKey ='outcome_id';
	protected $fillable=['outcome_name','description','active','company_id','location_id','organization_id'];
}