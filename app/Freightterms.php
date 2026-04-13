<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Freightterms extends Model
{
  protected $table='m_frieghtterms_t';
  protected $primaryKey='frieghtterm_id';
protected $fillable = [
      'frieghtterm_id','fob_point_name','fob_location', 'fob_barriers','fob_payment','source_type_id','start_date','end_date','active','created_by'
  ];
}
