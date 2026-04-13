<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticketsystemmaintenance extends Model
{
	protected $table='b_ticketsystem_t';
    protected $primaryKey='ticket_id';
    protected $fillable=['ticket_no','ticket_date','ticket_status','ticket_group','ticket_type','ticket_category','ticket_sub_category','ticket_severity','corrective_action','request_remarks','close_remarks','start_date','end_date','issue_created_by','issue_closed_by','company_id','location_id','organization_id','created_by','created_at','last_updated_by','updated_at','files'];
    }
