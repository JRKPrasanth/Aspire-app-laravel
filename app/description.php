<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class description extends Model
{
    protected $table='hr_description_t';
    protected $primaryKey='description_id';
    protected $fillable =['description_name','file','active'];
}
