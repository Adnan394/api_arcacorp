<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log_activity extends Model
{
    use softDeletes;
    protected $guarded = ['id'];
    
}