<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ServiceAdPhoto extends Model implements Transformable
{
    use TransformableTrait, Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_ad_photos';

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
    protected $fillable = ['service_ad_id', 'path', 'is_cover'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['is_cover' => 'boolean'];


    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['photo_url'];


    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /**
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        if($this->attributes['path'])
        {
            return $this->getFileUrl($this->attributes['path']);
        }

    }

    /**
     * @param $key
     * @return string
     */
    private function getFileUrl($key) {

        return (string) Storage::disk('media')->url($key);

    }

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service_ad()
    {
        return $this->belongsTo(ServiceAd::class);
    }

}
