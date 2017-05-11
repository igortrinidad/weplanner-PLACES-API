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
        'category_id',
        'name',
        'description',
        'city',
        'state',
        'address',
        'min_guests',
        'max_guests',
        'informations',
        'confirmed',
        'slug',
        'therms'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'informations' => 'json',
        'therms' => 'json',
        'confirmed' => 'boolean'
    ];

    public function photos()
    {
        return $this->hasMany(PlacePhoto::class);
    }

    public function documents()
    {
        return $this->hasMany(PlaceDocument::class);
    }

}
