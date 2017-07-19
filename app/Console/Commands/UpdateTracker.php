<?php

namespace App\Console\Commands;

use App\Models\Place;
use App\Models\PlaceTracking;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateTracker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tracker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the tracking number of all places';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('Started update place tracker');

        $reference = Carbon::now()->startOfMonth();

        $places = Place::select('id')->get();

        $this->info('Places found to increment: ' . $places->count());

        foreach($places as $place){

            $placeTracking = PlaceTracking::firstOrCreate(['place_id' => $place->id, 'reference' => $reference]);

            ($placeTracking->views <= 100) ? $min = 0 :  $min = $placeTracking->views / 100;
            ($placeTracking->views <= 400) ? $max = 20 :  $max = $placeTracking->views / 100;
            $increment = rand($min, $max);
            $permanence = $increment * 60;
            $newValViews = $placeTracking->views + $increment;
            $newValPermanence = $placeTracking->permanence + $permanence;

            $placeTracking->update([
                'views' => $newValViews,
                'permanence' => $newValPermanence,
            ]);
        }

        $this->info('Tracking increments is finished!');

    }
}
