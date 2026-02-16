<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    public $incrementing = false;
    protected $keyType = 'string';

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $last = self::orderBy('kd_menu', 'desc')->first();

            $number = $last
                ? intval(substr($last->kd_menu, -4)) + 1
                : 1;

            $model->kd_menu = 'MNU-' . now()->format('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }); 
    }
}
