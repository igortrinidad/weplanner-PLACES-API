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

    /* Navigate */
    .pagination > .active > span {
        background-color: #69a7be !important;
        border-color: #69a7be !important;
    }

</style>

@section('content')

@include('landing.partials.intro', ['show_logo' => false])

@include('landing.companies.search')

<section id="list-places" class="section">
    <div class="container">
        <div class="row">
            @foreach($promotions as $promotion)

                <div class="col-sm-3">
                    <a href="{{ route('places.show', $promotion->place->slug) }}">
                        <div class="card text-center">
                            <div class="cover" style="background-image: url('{{ $promotion->place->avatar }}')" alt="{{ $promotion->place->name }}">
                            </div>
                            <h2 class="m-t-10 m-b-0 t-overflow">{{ $promotion->place->name }}</h2>
                            <h3 class="location text-muted t-overflow m-t-10">
                                <i class="ion-ios-location"></i> {{ $promotion->place->city }} - {{ $promotion->place->state }}
                            </h3>

                            <a href="{{ route('places.show', $promotion->place->slug) }}" title="{{ $promotion->date }}" class="btn btn-success p-5 p-l-10 p-r-10 m-b-10">{{dataMysqlParaPtBr($promotion->date)}}</a>

                            <br>

                            <a href="{{ route('places.show', $promotion->place->slug) }}" title="{{ $promotion->place->name }}" class="btn btn-info p-5 p-l-10 p-r-10 m-b-10">Ver mais</a>
                        </div>
                    </a>
                </div>

            @endforeach
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                {!! $promotions->render() !!}
            </div>
        </div>
    </div>

    <div class="wrapper m-t-20">
        <div class="container text-center">
            <span class="wrapper-title">Não encontrou o local que procura ?</span><br>
            <a href="#" class="btn btn-primary m-t-10">Indique um local</a>
        </div>
    </div>

</section>
@stop

@section('scripts')
	@parent


@stop
