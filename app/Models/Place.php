<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Place extends Model implements Transformable
{
    use TransformableTrait, Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'places';

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
        'user_id',
        'cerimony',
        'party_space',
        'name',
        'description',
        'city',
        'state',
        'address',
        'min_guests',
        'max_guests',
        'style_rustic',
        'style_modern',
        'style_authentic',
        'localization_city',
        'localization_countryside',
        'accessibility',
        'parking',
        'covered',
        'outdoor',
        'informations',
        'confirmed',
        'slug',
        'therms',
        'instructions',
        'reservation_price',
        'pre_reservation_price'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cerimony' => 'boolean',
        'party_space' => 'boolean',
        'style_rustic' => 'boolean',
        'style_modern' => 'boolean',
        'style_authentic' => 'boolean',
        'localization_city' => 'boolean',
        'localization_countryside' => 'boolean',
        'accessibility' => 'boolean',
        'parking' => 'boolean',
        'covered' => 'boolean',
        'outdoor' => 'boolean',
        'address' => 'json',
        'informations' => 'json',
        'therms' => 'json',
        'confirmed' => 'boolean',
        'instructions' => 'json'
    ];

    protected $appends = ['appointments_count', 'reservations_count', 'pre_reservations_count', 'has_owner'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(PlacePhoto::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(PlaceDocument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(PlaceAppointment::class);
    }

    /**
     * @return mixed
     */
    public function getAppointmentsCountAttribute()
    {
        return $this->hasMany(PlaceAppointment::class)->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function calendar_settings()
    {
        return $this->hasOne(PlaceCalendarSettings::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(PlaceReservations::class)->with('client');
    }

    /**
     * @return mixed
     */
    public function getReservationsCountAttribute()
    {
        return $this->hasMany(PlaceReservations::class)->where('is_pre_reservation', false)->count();
    }

    /**
     * @return mixed
     */
    public function getPreReservationsCountAttribute()
    {
        return $this->hasMany(PlaceReservations::class)->where('is_pre_reservation', true)->count();
    }

    /**
     * @return mixed
     */
    public function gethasOwnerAttribute()
    {
        return $this->user_id  != null;
    }

}
