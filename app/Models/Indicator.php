<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** @mixin Builder */
class Indicator extends Model
{
    protected $fillable = ['code'];
    protected $visible = ['id', 'code'];
}