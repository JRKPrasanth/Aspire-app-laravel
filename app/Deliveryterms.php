<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Deliveryterms extends model  {

	protected $table='m_delivery_terms_t';

    protected $primaryKey='delivery_terms_id';
  protected $fillable = [
        'delivery_term_name','source_type_id', 'start_date','end_date','remarks','active'
    ];


}
