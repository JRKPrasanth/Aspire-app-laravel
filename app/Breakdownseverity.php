<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Breakdownseverity extends Model
{
	protected $table='breakdown_severity';
    protected $primaryKey='breakdownseverity_id';
    protected $fillable=['severity_name','description'];
    }