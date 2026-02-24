<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class DetailJual extends Model
{
    use LogsActivity;

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
            ->useLogName('detail-jual')
            ->logOnly([
               'jual_id',
                'menu_id',
                'harga_satuan',
                'jumlah',
                'sisa',
                'laku',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
