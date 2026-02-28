<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Jenis extends Model
{
    use LogsActivity;

    protected $table = 'jenis';

    protected $fillable = ['kd_jenis', 'nama_jenis'];

    public function menu()
    {
        return $this->hasMany(Menu::class, 'jenis_id', 'kd_jenis');
    }

    public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
            'ip_address' => request()?->ip(),
        ]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('jenis')
            ->logOnly([
               'nama_jenis'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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
