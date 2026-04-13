<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountstructure extends Model
{
    protected $table='f_account_structure_t';
      protected $primaryKey='f_account_structure_id';
      protected $fillable =['f_account_structure_id','company_id','location_id','costcenter_id','main_account_id','sub_account_id','future_reference1','future_reference2','concatenated_segments'
      ,'rpt_grp','rpt_type','rpt_seq'];
}
