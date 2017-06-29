<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class OwnerRequestDocument extends Model implements Transformable
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
        'owner_request_id',
        'path',
        'filename',
        'extension'
    ];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['url'];

    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /**
     * @return string
     */
    public function getUrlAttribute()
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

        return (string) \Storage::disk('media')->url($key);

    }

}
