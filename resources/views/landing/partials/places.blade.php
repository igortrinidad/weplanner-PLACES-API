<style media="screen">
    .t-overflow {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        width: 100%;
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

<section id="featured_places" class="section">
  <div class="container">
    <!--|Section Header|-->
    <header class="section-header text-center wow tada" data-wow-delay=".05s">
      <div class="row">
        <div class="col-md-6 block-center">
          <h2 class="section-title">Espaços em destaque</h2>
          <p>Confira alguns espaços <b>em destaque</b> na plataforma We PLaces.</p>
        </div>
      </div>
    </header> <!--|End Section Header|-->

    <div class="row wow fadeIn">
        @foreach($places as $place)
            <div class="col-sm-3">
                <a href="{{ route('places.show', $place->slug) }}">
                    <div class="card text-center">
                        <div class="cover" style="background-image: url('{{ $place->avatar }}')" alt="{{ $place->name }}">
                        </div>
                        <h2 class="m-t-10 m-b-0 t-overflow">{{ $place->name }}</h2>
                        <h3 class="location text-muted t-overflow m-t-10">
                            <i class="ion-ios-location"></i> {{ $place->city }} - {{ $place->state }}
                        </h3>

                        <a href="{{ route('places.show', $place->slug) }}" title="{{ $place->name }}" class="btn btn-info p-5 p-l-10 p-r-10 m-b-10">Ver o local</a>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

  </div>
</section>
