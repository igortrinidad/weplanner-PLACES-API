<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class AdvertiserSocialProvider extends Model implements Transformable
{
    use TransformableTrait, Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advertiser_social_providers';


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
        'advertiser_id', 'provider', 'provider_id', 'access_token'
    ];

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */
    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

}
