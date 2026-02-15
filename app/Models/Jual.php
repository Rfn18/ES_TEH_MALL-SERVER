<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jual extends Model
{
    protected $table = 'jual';

    public function stand(): BelongsTo
    {
        return $this->BelongsTo(Stand::class);
    }

    protected $fillable = [
        'no_transaksi',
        'stand',
        'total_biaya_produksi',
        'total_omzet',
        'selisih',
        'tanggal',
    ];
}
