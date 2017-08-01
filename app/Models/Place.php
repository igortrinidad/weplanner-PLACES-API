<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Place extends Model implements Transformable
{
    use TransformableTrait, Uuids, SoftDeletes;

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
        'plan',
        'name',
        'description',
        'phone',
        'email',
        'website',
        'whatsapp',
        'city',
        'state',
        'address',
        'min_guests',
        'max_guests',
        'style_rustic',
        'style_modern',
        'style_authentic',
        'style_classic',
        'localization_city',
        'localization_countryside',
        'accessibility',
        'parking',
        'covered',
        'outdoor',
        'informations',
        'confirmed',
        'is_active',
        'slug',
        'therms',
        'instructions',
        'reservation_price',
        'pre_reservation_price',
        'virtual_tour_url',
        'featured_position',
        'show_all_informations',
        'created_by_id',
        'created_by_type',
        'list_common'
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
        'is_active' => 'boolean',
        'instructions' => 'json',
        'list_common' => 'boolean',
        'show_all_informations' => 'boolean',
        'featured_position' => 'integer',
        'reservation_price' => 'double',
        'pre_reservation_price' => 'double',
    ];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['appointments_count', 'reservations_count', 'pre_reservations_count', 'has_owner', 'has_virtual_tour', 'total_views'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_by_id', 'created_by_type'];

    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /**
     * @return mixed
     */
    public function getAppointmentsCountAttribute()
    {
        return $this->hasMany(PlaceAppointment::class)->count();
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
        return $this->user_id != null;
    }

    /**
     * @return mixed
     */
    public function gethasVirtualTourAttribute()
    {
        return $this->virtual_tour_url != null;
    }

    /**
     * @return mixed
     */
    public function getTotalViewsAttribute()
    {
        return $this->hasMany(PlaceTracking::class)->get()->sum('views');
    }

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

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
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function created_by()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->hasMany(PlaceVideo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tracking()
    {
        return $this->hasMany(PlaceTracking::class, 'place_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function promotional_dates()
    {
        return $this->hasMany(PromotionalDate::class, 'place_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function decorations()
    {
        return $this->hasMany(Decoration::class, 'place_id', 'id');
    }


}
