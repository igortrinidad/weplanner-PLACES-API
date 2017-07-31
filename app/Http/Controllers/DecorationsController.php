<?php

namespace App\Http\Controllers;

use App\Models\DecorationPhoto;
use App\Repositories\AdTrackingRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Decoration;



class DecorationsController extends Controller
{
    /**
     * @var AdTrackingRepository
     */
    private $adTrackingRepository;

    /**
     * DecorationsController constructor.
     * @param AdTrackingRepository $adTrackingRepository
     */
    public function __construct(AdTrackingRepository $adTrackingRepository)
    {
        $this->adTrackingRepository = $adTrackingRepository;
    }

    public function index($place_id)
    {
        $decorations = Decoration::where('place_id', $place_id)->with('advertiser', 'photos', 'videos')->get();

        return response()->json(['data'    => $decorations->toArray(),]);
    }


	/**
     * Decoration store
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $decoration = Decoration::create($request->all());

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
               DecorationPhoto::find($photo['id'])->update($photo);
            }
        }

        $response = [
            'message' => 'Decoration created.',
            'data'    => $decoration->toArray(),
        ];

        if ($request->wantsJson()) {

            return response()->json($decoration);
        }

    }

    /**
     * Decoration show
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $decoration = Decoration::with('advertiser', 'photos', 'videos', 'tracking')->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $decoration,
            ]);
        }

    }


    /**
     * Decoration update
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $decoration = Decoration::find($request->get('id'));

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                DecorationPhoto::find($photo['id'])->update($photo);
            }
        }

        $decoration->update($request->all());

        $response = [
            'message' => 'Decoration updated.',
            'data'    => $decoration->toArray(),
        ];

        if ($request->wantsJson()) {

            return response()->json($decoration);
        }

    }


    /**
     * Decoration Destroy
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $decoration = Decoration::destroy($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Decoration deleted.',
                'deleted' => $decoration,
            ]);
        }
    }

    public function placeDecorations($place_id)
    {
        $reference = Carbon::now()->startOfMonth()->format('Y-m-d');

        $decorations = Decoration::where('place_id', $place_id)->with('advertiser', 'photos', 'videos')->get();

        foreach($decorations as $decoration){
            $tracking = $this->adTrackingRepository
                ->firstOrCreate([
                    'ad_id' => $decoration->id,
                    'ad_type' => get_class($decoration),
                    'reference' => $reference
                ]);

            $tracking->increment('exhibitions');
        }

        return response()->json(['data'    => $decorations->toArray(),]);
    }
}