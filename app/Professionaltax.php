<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Professionaltax extends Model
{
     protected $table='hr_professional_tax_hdr';
    protected $primaryKey='ptax_id';
    protected $fillable = ['ptax_state_id','description','status'];
}
