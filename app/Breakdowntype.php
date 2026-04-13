<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Breakdowntype extends Model
{
	protected $table='m_breakdowntype_t';
    protected $primaryKey='breakdowntype_id';
    protected $fillable=['breakdown_name','description','created_by'];
    }

