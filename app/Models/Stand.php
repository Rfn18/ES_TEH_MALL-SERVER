<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    protected $table = 'stands';
    protected $primaryKey = 'kd_stand';
    public $incrementing = false;
    protected $keyType = 'string';

    public function jual()
    {
        return $this->hasMany(Jual::class, 'stand_id', 'kd_stand');
    }

    protected $fillable = [
        'kd_stand',
        'nama_stand',
    ];

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $last = self::orderBy('kd_stand', 'desc')->first();

            $number = $last
                ? intval(substr($last->kd_stand, -4)) + 1
                : 1;

            $model->kd_stand = 'STD-' . now()->format('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }); 
    }
}
