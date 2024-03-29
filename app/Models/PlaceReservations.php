<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Carbon\Carbon as Carbon;

class PlaceReservations extends Model implements Transformable
{
    use TransformableTrait, Uuids;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'place_reservations';

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
        'client_id',
        'date',
        'all_day',
        'is_pre_reservation',
        'is_confirmed',
        'confirmed_at',
        'is_canceled',
        'canceled_at',
        'therms',
        'history'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_pre_reservation' => 'boolean',
        'is_confirmed' => 'boolean',
        'is_canceled' => 'boolean',
        'all_day' => 'boolean',
        'therms' => 'json',
        'history' => 'json',
    ];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['day', 'time'];


    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /**
     * @return mixed
     */
    public function getDayAttribute()
    {

        return Carbon::createFromFormat('Y-m-d H:i:s', $this->date)->format('Y-m-d');
    }

    /**
     * @return mixed
     */
    public function getTimeAttribute()
    {

        return Carbon::createFromFormat('Y-m-d H:i:s', $this->date)->format('H:i');
    }



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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
