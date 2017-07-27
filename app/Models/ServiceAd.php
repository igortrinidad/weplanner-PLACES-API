<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ServiceAd extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_ads';

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
        'id',
        'advertiser_id',
        'expire_at',
        'place_id',
        'type',
        'action',
        'title',
        'description',
        'action_data',
        'city',
        'state',
        'is_active'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['action_data' => 'json', 'is_active' => 'boolean'];



    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['type_label', 'action_label'];


    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /**
     * @return mixed
     */
    public function getTypeLabelAttribute()
    {
        if ($this->type == 'home') {
            return 'Anúncio na home page';
        }

        if ($this->type == 'city') {
            return 'Anúncio em cidade';
        }

        if ($this->type == 'place') {
            return 'Anúncio em local';
        }
    }


    /**
     * @return mixed
     */
    public function getActionLabelAttribute()
    {
        if ($this->action == 'email') {
            return 'E-mail';
        }

        if ($this->action == 'call') {
            return 'Ligação';
        }

        if ($this->action == 'whatsapp') {
            return 'Whatsapp';
        }
    }

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(ServiceAdPhoto::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tracking()
    {
        return $this->hasMany(AdTracking::class, 'ad_id');
    }

}



