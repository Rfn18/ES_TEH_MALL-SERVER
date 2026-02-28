<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Jual extends Model
{
    use LogsActivity;

    protected $table = 'juals';

    public function stand():BelongsTo
    {
        return $this->BelongsTo(Stand::class);
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function menu() {
         return $this->hasMany(DetailJual::class, 'jual_id', 'no_transaksi');
    }

    protected $fillable = [
        'no_transaksi',
        'user_id',
        'stand_id',
        'total_biaya_produksi',
        'total_omzet',
        'selisih',
        'tanggal',  
    ];

     public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
            'ip_address' => request()?->ip(),
        ]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('jual')
            ->logOnly([
                'stand_id',
                'total_biaya_produksi',
                'total_omzet',
                'selisih',
                'tanggal',  
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


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
