<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
protected $table='app_institution_t';
    protected $primaryKey='institution_id';
	protected $fillable =['institution_id','name','key_contact1','key_contact2','mail1','mail2','conatct_name1','conatct_name2','desgination1','desgination2','location_id','company_id','organization_id','created_by','last_updated_by','created_at','updated_at'];
	 }
