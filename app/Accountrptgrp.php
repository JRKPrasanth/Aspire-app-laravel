<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountrptgrp extends Model
{
    protected $table='f_account_reporting_group_t';
      protected $primaryKey='acc_rpt_grp_id';
      protected $fillable =['rpt_grp','rpt_type','rpt_seq'];
}
