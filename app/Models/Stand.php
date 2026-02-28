<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Stand extends Model
{
    use LogsActivity;

    protected $table = 'stands';

    public function jual()
    {
        return $this->hasMany(Jual::class, 'stand_id', 'kd_stand');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'stand_id', 'kd_stand');
    }

    protected $fillable = [
        'kd_stand',
        'lokasi',
        'nama_stand',
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
            ->useLogName('stand')
            ->logOnly([
                'lokasi',
                'nama_stand', 
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


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
