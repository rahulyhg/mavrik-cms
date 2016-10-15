<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreLaunch extends Model
{
    protected $fillable = ['position', 'text', 'mobile', 'link', 'launch'];
}
