<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class travelclaim extends Model
{
   protected $table='hr_employee_travel_claim_t';
     protected $primaryKey='travel_claim_id';
    protected $fillable=['employee_id','claim_title','description','travel_purpose','travel_date','travel_to_date','travel_mode','from_place','to_place',
                    'distance','rate','bill_amount','bill_reason','approve_amount',
                    'approve_by','approve_date','bill_copy','status','out_local'];
}
