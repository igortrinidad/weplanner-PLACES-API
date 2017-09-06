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
    .section.gray { background-color: #FBFBFB; }
    /* Card Adjusts */

    .card h2, .card .name {
        font-size: 16px;
        text-align: center;
    }
</style>

@section('content')
<section id="list-places" class="section gray">
    <div class="container">
        <div class="row">
            @foreach($places as $place)
                <div class="col-sm-3">
                    <div class="card">
                        @foreach($place->photos as $photo)
                            @if($photo->is_cover)
                            <div class="cover" style="background-image: url('https://s3.amazonaws.com/weplanner-places-development/{{ $photo->path }}')" alt="{{ $place->name }}">
                            </div>
                            @endif
                        @endforeach
                        <h2 class="m-t-10 t-overflow">{{ $place->name }}</h2>
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
