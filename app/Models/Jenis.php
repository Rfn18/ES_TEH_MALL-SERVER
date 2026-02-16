<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    protected $table = 'jenis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kd_jenis', 'nama_jenis'];

    public function menu()
    {
        return $this->hasMany(Menu::class, 'jenis_id', 'kd_jenis');
    }

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $last = self::orderBy('kd_jenis', 'desc')->first();

            $number = $last
                ? intval(substr($last->kd_jenis, -4)) + 1
                : 1;

            $model->kd_jenis = 'JNS-' . now()->format('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }
}
