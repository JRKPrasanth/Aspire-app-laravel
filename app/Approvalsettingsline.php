<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approvalsettingsline extends Model
{
    protected $table='m_approvalsettings_line_t';
    protected $primaryKey='approvalsettings_line_id';
    protected $foreignKey='approvalsettings_hdr_id';
    protected $fillable=['approvalsettings_hdr_id','value_from','value_to','approve_required','approver_id','comments'];
 
}
