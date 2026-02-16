<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jual extends Model
{
    protected $table = 'juals';
    public $incrementing = false;
    protected $keyType = 'string';


    public function stand():BelongsTo
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $last = self::orderBy('no_transaksi', 'desc')->first();

            $number = $last
                ? intval(substr($last->no_transaksi, -4)) + 1
                : 1;

            $model->no_transaksi = 'TRS-' . now()->format('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }); 
    }
}
