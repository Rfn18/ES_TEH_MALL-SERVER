<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponentColor extends Model
{
    protected $table = "component_colors";
    protected $fillable = [
        'component',
        'color'
    ];
}
