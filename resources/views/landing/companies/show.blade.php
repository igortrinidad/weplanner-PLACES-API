<style media="screen">
    .first {
        margin: 56px 0 0 0;
        padding: 30px 0 50px 0;
    }
</style>
@extends('landing.companies.index')

@section('content')

<section class="section first">
    <h1>{{ $place->name }}</h1>
</section>

@stop

@section('scripts')
	@parent


@stop
