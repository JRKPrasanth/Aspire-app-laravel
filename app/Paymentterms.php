<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Paymentterms extends Model{

    protected $table='m_payment_terms_t';
    protected $primaryKey='payment_term_id';
    protected $fillable =['payment_term_name','payment_terms_type','description','active','payment_days'];
}
