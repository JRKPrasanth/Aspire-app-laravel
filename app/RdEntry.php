<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RdEntry extends Model
{

     protected $table = 'rd_entries';
     protected $primaryKey='id';
    protected $fillable = [
        'project_name','batch_no','product_name',
        'test_parameters','test_results','test_observations',
        'process_steps','process_remarks',
        'output_summary','output_status','created_by',
        'current_stage','approval_status','review_comments',
        'company_id','location_id','organization_id',
        'attachments'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];
}   