<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PlaceCategory extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [];

}
