<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class qcapprovalsettings extends Model
{
    //
  protected $table='m_qcapproval_settings_t';
  protected $primaryKey='qcapproval_id';

  protected $fillable=['product_group_id','product_category_id','qc_checker_id','qc_approver_id','created_by','created_at','updated_by','updated_at','company_id','organization_id','location_id'];
}
