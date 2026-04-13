<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class checklist extends Model
{
	protected $table='checklist_tbl';
    protected $primaryKey='checklist_id';
    protected $fillable=['checklist_name','file','frequency_name','created_at','updated_at'];
    }

