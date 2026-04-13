<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobposition extends Model
{
    protected $table='m_position';
    protected $primaryKey='position_id';
    protected $fillable=['position','position_description','organization_id','company_id','active','last_updated_by','created_by'];
}
