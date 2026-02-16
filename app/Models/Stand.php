<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    protected $table = 'stand';
     protected $primaryKey = 'stand_id';

    public function jual()
    {
        return $this->hasMany(Jual::class, 'stand_id', 'kd_stand');
    }

    protected $fillable = [
        'kd_stand',
        'nama_stand',
    ];
}
