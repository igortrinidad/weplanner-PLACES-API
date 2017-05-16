<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PlaceAppointment extends Model implements Transformable
{
    use TransformableTrait, Uuids;

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
        'title',
        'allDay',
        'start',
        'end',
        'url',
        'className',
        'editable',
        'startEditable',
        'durationEditable',
        'resourceEditable',
        'rendering',
        'overlap',
        'constraint',
        'source',
        'color',
        'backgroundColor',
        'borderColor',
        'textColor',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'allDay' => 'boolean',
        'editable' => 'boolean',
        'startEditable' => 'boolean',
        'durationEditable' => 'boolean',
        'resourceEditable' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

}
