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
        padding-bottom: 0 !important;
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

@include('landing.companies.search')

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

                        <a href="/buscar/{{ $place->slug }}" title="{{ $place->name }}" class="btn btn-info p-5 p-l-10 p-r-10 m-b-10">Ver o local</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-sm-12">
                {!! $places->render() !!}
            </div>
        </div>
    </div>

    <div class="wrapper m-t-20">
        <div class="container text-center">
            <span class="wrapper-title">Não encontrou o local que procura ?</span><br>
            <a href="#" class="btn btn-primary m-t-10">Indique para nós</a>
        </div>
    </div>

</section>
@stop

@section('scripts')
	@parent


@stop
