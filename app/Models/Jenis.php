<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    protected $table = 'jenis';

    protected $fillable = ['kd_jenis', 'nama_jenis'];

    public function menu()
    {
        return $this->hasMany(Menu::class, 'jenis_id', 'kd_jenis');
    }
}
