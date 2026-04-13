<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class poreceiptmethod extends Model
{
    protected $table='m_receipt_method_t';
	protected $primaryKey='receipt_method_id';
	protected $fillable =['receipt_method','description','active'];
}
