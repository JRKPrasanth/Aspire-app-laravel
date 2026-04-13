<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companydocument extends Model
{
    protected $table='m_company_check_list';
    protected $primaryKey='id';
    protected $fillable=['document','company_id','active','last_updated_by','created_by'];
}
