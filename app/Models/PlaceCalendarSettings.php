<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PlaceCalendarSettings extends Model implements Transformable
{
    use TransformableTrait, Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'place_calendar_settings';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_id',
        'available_dates_range',
        'available_days_config',
        'calendar_is_public',
        'calendar_is_active',
        'workday_is_active',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'available_dates_range' => 'json',
        'available_days_config' => 'json',
        'calendar_is_public' => 'boolean',
        'calendar_is_active' => 'boolean',
        'workday_is_active' => 'boolean',

    ];


    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

}
