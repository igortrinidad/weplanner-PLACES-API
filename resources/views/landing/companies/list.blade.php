@extends('landing.companies.index')

<style media="screen">
    .t-overflow {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        width: 100%;
    }

    .section {
        position: relative;
        width: 100%; height: auto;
    }
    .cover {
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
    }
    .card .cover {
        width: 100%; height: 150px;
        border-radius: 4px;
    }
    /* Card Adjusts */

    .card h2, .card .name {
        font-size: 16px;
        text-align: center;
    }

    .card .location { font-size: 15px; }
    .text-muted { color: #4a5464 !important; }
    .card .btn {
        text-transform: none;
    }
</style>

@section('content')
<section id="list-places" class="section">
    <div class="container">
        <div class="row">
            @foreach($places as $place)
                <div class="col-sm-3">
                    <div class="card text-center">
                        @foreach($place->photos as $photo)
                            @if($photo->is_cover)
                            <div class="cover" style="background-image: url('https://s3.amazonaws.com/weplanner-places-development/{{ $photo->path }}')" alt="{{ $place->name }}">
                            </div>
                            @endif
                        @endforeach
                        <h2 class="m-t-10 m-b-0 t-overflow">{{ $place->name }}</h2>
                        <h3 class="location text-muted t-overflow m-t-10">
                            <i class="ion-ios-location"></i> {{ $place->city }} - {{ $place->state }}
                        </h3>

                        <a href="#" class="btn btn-info p-5 p-l-10 p-r-10 m-b-10">Ver local</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@stop

@section('scripts')
	@parent


@stop
