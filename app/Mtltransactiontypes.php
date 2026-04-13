<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mtltransactiontypes extends Model
{
    protected $table='m_transaction_types_t';
	protected $primaryKey='transaction_type_id';
	protected $fillable=['transaction_type_code','transaction_type_name','transaction_source_id','transaction_action_id','description','active'];
}
