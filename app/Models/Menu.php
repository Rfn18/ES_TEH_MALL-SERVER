<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id', 'kd_jenis');
    }

    protected $fillable = [
        'kd_menu',
        'nama_menu',
        'jenis_id',
        'biaya_produksi',
        'harga_satuan',
    ];
}
