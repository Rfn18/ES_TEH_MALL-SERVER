<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailJual extends Model
{
     public function jual():BelongsTo
    {
        return $this->BelongsTo(Jual::class);
    }

    public function menu():BelongsTo
    {
        return $this->BelongsTo(Menu::class);
    }

    protected $fillable = [
        'jual_id',
        'menu_id',
        'harga_satuan',
        'jumlah',
        'sisa',
        'laku',
        'subtotal_biaya_produksi',
        'omzet',
    ];
}
