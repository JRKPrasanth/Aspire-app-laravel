<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Breakdownmaintenance extends Model
{
	protected $table='b_maintenance_t';
    protected $primaryKey='id';
    protected $fillable=['allocate_engineer_by','allocate_engineer_on','request_request_by','request_request_on','request_approve_by','request_approve_on','closed_engineer_by','issue_created_on','closed_engineer_on','issue_created_by','machine_id','department_id','break_type_id','issue_date','breakdown_sevearity','causes','maintenance_type','company_id','location_id','organization_id','created_by','created_at','last_updated_by','updated_at','active','engineer','technician','request_status','critical_spare','others','start_date','corrective_action','preventive_action','end_date','ticket_number','ticket_count','is_breakdown','request_remark','approve_remarks','shift','critical_spare','error_code','files'];
    }
