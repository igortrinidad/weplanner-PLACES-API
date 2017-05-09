<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PlacePhoto extends Model implements Transformable
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
    protected $fillable = ['place_id', 'path', 'is_cover'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['is_cover' => 'boolean'];


    /*
    * Append the photo_url to a record
    */
    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {
        if($this->attributes['path'])
        {
            return $this->getFileUrl($this->attributes['path']);
        }

    }

    private function getFileUrl($key) {

        return (string) Storage::disk('media')->url($key);

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

}
