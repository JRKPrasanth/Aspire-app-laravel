<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taxgroup extends Model
{
    protected $table = 'm_tax_group_t';
    protected $primaryKey ='tax_group_id';
    protected $fillable =['tax_group_id','tax_group_name','display_name'];

}
