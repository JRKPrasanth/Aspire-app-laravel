<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lettercontent extends Model
{
    protected $table='m_letter_content';
    protected $primaryKey='id';
    protected $fillable=['letter_type','body_content','approve_status','employee_type','active','created_at','updated_at','last_updated_by','created_by','company_id','location_id','organization_id'];
}
