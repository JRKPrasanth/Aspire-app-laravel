<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Breakdownmaintenancelines extends Model
{
	protected $table='b_maintenance_t_lines';
    protected $primaryKey='bm_lines_id';
    protected $foreignKey='id';
    protected $fillable=['id','spares_id','qty','inventory_stock','company_id','location_id','organization_id','created_by','created_at','last_updated_by','updated_at'];
	
	
    }
