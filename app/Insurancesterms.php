<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insurancesterms extends Model
{
    protected $table='m_insurance_terms_t';
    protected $primaryKey='insurance_term_id';
    protected $fillable =['insurance_term_name','insurance_terms_type','description','active'];
    
}
