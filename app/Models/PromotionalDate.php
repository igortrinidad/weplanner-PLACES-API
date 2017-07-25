<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PromotionalDate extends Model implements Transformable
{
    use TransformableTrait, Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotional_dates';

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
        'date',
        'all_day',
        'slots',
        'title',
        'value',
        'discount',
        'rules'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'double',
        'discount' => 'double',
        'all_day' => 'boolean',
        'slots' => 'json'
        ];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['is_reserved', 'available_slots'];

    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /**
     * @return mixed
     */
    public function getIsReservedAttribute()
    {
        if($this->all_day){
            $date = Carbon::createFromFormat('Y-m-d', $this->date)->startOfDay()->format('Y-m-d H:i:s');

            $count = PlaceReservations::where('place_id', $this->place_id)
                ->where('date', $date)
                ->where('is_confirmed', true)->count();

            return $count > 0 ? true : false;
        }

        if(!$this->all_day){
            $slots = $this->slots;

            $available = [];
            foreach($slots as $slot){
                $date = Carbon::createFromFormat('Y-m-d H:i', $this->date.' '.$slot['hour'])->format('Y-m-d H:i:s');

                $count = PlaceReservations::where('place_id', $this->place_id)
                    ->where('date', $date)
                    ->where('is_confirmed', true)->count();

                if(!$count){
                    $available[] = $slot;
                }
            }
            return count($available) === 0;
        }



    }


    public function getAvailableSlotsAttribute()
    {
        $available = [];

        if(!$this->all_day){
            $slots = $this->slots;
            foreach($slots as $slot){
                $date = Carbon::createFromFormat('Y-m-d H:i', $this->date.' '.$slot['hour'])->format('Y-m-d H:i:s');

                $count = PlaceReservations::where('place_id', $this->place_id)
                    ->where('date', $date)
                    ->where('is_confirmed', true)->count();

                if(!$count){
                    $available[] = $slot;
                }
            }

        }
        return $available;
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


}
