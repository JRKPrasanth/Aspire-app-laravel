<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paymentmethods extends Model
{
  protected $table='m_payment_methods_t';
      protected $primaryKey='payment_method_id';
      protected $fillable =['payment_method_name','payment_method_type','description','active','created_by'];
}
