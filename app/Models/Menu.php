<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Menu extends Model
{
    use LogsActivity;

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

    public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
            'ip_address' => request()?->ip(),
        ]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('menu')
            ->logOnly([
                'nama_menu',
                'jenis_id',
                'harga_satuan',
                'biaya_produksi',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

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
