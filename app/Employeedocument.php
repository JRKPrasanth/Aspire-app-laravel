<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeedocument extends Model
{
    protected $table='m_employee_doc_check_list';
    protected $primaryKey='id';
    protected $fillable=['emp_document','organization_id','company_id','active','last_updated_by','created_by'];
}
