<style media="screen">
    .section {
        color: #4a5464;
    }
    .swiper-container.place-photos {
        height: 300px !important;
        margin-top: -40px;
    }
    .place-cover {
        width: 100%; height: 300px !important;
    }
    .place-content {
        width: 100%;
        position: relative;
    }
    /* Interactions */
    .btn.out { display: none !important; }
    .info { display: none; }
    .info.in { display: block; }

    /* List */
    .list-group-item.title {
        background-color: #f2f2f2;
    }
</style>
@extends('landing.companies.index')

@section('content')

<section class="section">

    <div class="swiper-container place-photos">
        <div class="swiper-wrapper">
            @foreach($place->photos as $photo)
                <div class="swiper-slide cover place-cover" style="background-image: url('{{ $photo->photo_url }}')"></div>
            @endforeach
        </div>
        <div class="swiper-button-prev swiper-button-black"></div>
        <div class="swiper-button-next swiper-button-black"></div>
    </div>

    <div class="place-content m-t-20">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h1 class="m-0">{{ $place->name }}</h1>
                        </div>
                    </div>
                </div>

                <!-- LEFT COL -->
                <div class="col-sm-5">
                    <!-- Base Informations -->
                    <div class="card">
                        <div class="card-header text-center">
                            @foreach($place->photos as $photo)
                                @if($photo->is_cover)
                                    <div class="cover" style="background-image: url('{{ $photo->photo_url }}')"></div>
                                @endif
                            @endforeach
                        </div>

                        <div class="card-body card-padding text-center">

                            <!-- Price -->
                            <div class="title">
                                @if($place->infomations['starter_price'] > 0)
                                    <strong class="strong-small">
                                        {{ $place->informations['starter_price'] }}
                                        <small>(a partir)</small>
                                    </strong>
                                @else
                                    <strong class="strong-small">
                                        Valor não disponível
                                    </strong>
                                @endif
                            </div>
                            <!-- Price -->

                            <!-- Address -->
                            <div class="m-t-10">
                                <i class="ion-ios-location m-r-5"></i>
                                <span>{{ $place->address['full_address'] }}</span>
                            </div>
                            <!-- Address -->

                            <!-- Categories -->
                            <div class="m-t-10">

                            </div>
                            <!-- Categories -->

                            <!-- Phone -->
                            @if($place->phone)
                                <div class="m-t-15">
                                    <button type="button" class="btn btn-primary btn-target" data-target="#company-phone">Mostrar telefone</button>
                                    <div class="info" id="company-phone">
                                        <i class="ion-ios-telephone m-r-5"></i>
                                        <span class="f-16"><a href="tel:{{ $place->phone }}">{{ $place->phone }}</a></span>
                                    </div>
                                </div>
                            @endif
                            <!-- Phone -->

                            <!-- Website -->
                            @if($place->website)
                                <div class="m-t-10">
                                    <button type="button" class="btn btn-sm btn-primary btn-target" data-target="#company-website">Mostrar Website</button>
                                    <div class="info" id="company-website">
                                        <i class="ion-ios-world-outline m-r-5"></i>
                                        <span class="f-16"><a href="{{ $place->website }}" target="_blank">{{ $place->website }}</a></span>
                                    </div>
                                </div>
                            @endif
                            <!-- Website -->

                            <button type="button" class="btn btn-xs btn-block btn-facebook m-t-20 p-5 f-15" @click="openShareFacebook()">
                                <i class="ion-social-facebook m-r-5" ></i>Compartilhar no facebook
                            </button>
                            <button type="button" class="btn btn-xs btn-block btn-whatsapp m-t-5 p-5 f-15" @click="openShareWhatsapp()">
                                <i class="ion-social-whatsapp m-r-5"></i>Compartilhar no whatsapp
                            </button>

                        </div>
                    </div>
                    <!-- /Base Informations -->

                    <!-- Description -->
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2>Descrição</h2>
                        </div>
                        <div class="card-body card-padding">
                            <p class="text-justify">{{ $place->description }}</p>
                        </div>
                    </div>
                    <!-- / Description -->

                    <!-- Workdays -->
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2>Dias e Horários</h2>
                        </div>
                        <div class="card-body card-padding text-center">
                            @if($place->calendar_settings->workday_is_active)
                                <span>Dias e horários de funcionamento não cadastrados.</span>
                            @else
                                <button type="button" class="btn btn-primary">Ver dias e Horários</button>
                            @endif
                        </div>
                    </div>
                    <!-- / Workdays -->

                </div>
                <!-- / LEFT COL -->

                <!-- RIGHT COL -->
                <div class="col-sm-7">
                    <!-- Extra Informations -->
                    <div class="card">
                        <div class="card card-header ch-alt text-center">
                            <h2>Informações</h2>
                        </div>
                        <div class="card-body">
                            <div class="m-b-20" v-show="interactions.showMoreInfo || isDesktop">

                                <!-- Capacidade -->
                                <ul class="list-group">
                                    <li class="list-group-item title"><strong>Capacidade do Local</strong></li>
                                    <li class="list-group-item">Mínimo de convidade <span class="badge badge-success m-l-5">{{ $place->min_guests }}</span></li>
                                    <li class="list-group-item">Máximo de convidados <span class="badge badge-success m-l-5">{{ $place->max_guests }}</span></li>
                                </ul>
                                <!-- /Capacidade -->

                                <!-- Servicos -->
                                <ul class="list-group">
                                    <li class="list-group-item title"><strong>Serviços</strong></li>
                                    <li class="list-group-item">Cerimônia <span class="badge badge-success m-l-5">{{ $place->cerimony ? 'sim' : 'não' }}</span></li>
                                    <li class="list-group-item">Festa / Recepção <span class="badge badge-success m-l-5">{{ $place->party_space ? 'sim' : 'não' }}</span></li>
                                    <li class="list-group-item">Acessibilidade <span class="badge badge-success m-l-5">{{ $place->accessibility ? 'sim' : 'não' }}</span></li>
                                    <li class="list-group-item">Estacionamento <span class="badge badge-success m-l-5">{{ $place->parking ? 'sim' : 'não' }}</span></li>
                                    <li class="list-group-item">Espaço coberto <span class="badge badge-success m-l-5">{{ $place->covered ? 'sim' : 'não' }}</span></li>
                                    <li class="list-group-item">Espaço ao ar livre <span class="badge badge-success m-l-5">{{ $place->outdoor ? 'sim' : 'não' }}</span></li>
                                </ul>
                               <!-- /Servicos -->

                               <!-- Time -->
                               <ul class="list-group">
                                   <li class="list-group-item title"><strong>Horário</strong></li>
                                   <li class="list-group-item">Possui horário limete ? <span class="badge badge-success m-l-5">{{ $place->informations['time_limit'] ? 'sim' : 'não' }}</span></li>
                                   @if($place->informations['time_limit'])
                                        <li class="list-group-item">Horário até <span class="badge badge-success m-l-5">{{ $place->informations['time_limit_value'] }}</span></li>
                                   @endif
                               </ul>
                              <!-- / Time -->

                              <!-- Time -->
                              <ul class="list-group">
                                  <li class="list-group-item title"><strong>Exclusividade de fornecedores</strong></li>
                                  <li class="list-group-item">Exclusividade Buffet <span class="badge badge-success m-l-5">{{ $place->informations['buffet_exclusivity'] ? 'sim' : 'não' }}</span></li>
                                  <li class="list-group-item">Exclusividade Decoração<span class="badge badge-success m-l-5">{{ $place->informations['decoration_exclusivity'] ? 'sim' : 'não' }}</span></li>
                                  <li class="list-group-item">Exclusividade Barman<span class="badge badge-success m-l-5">{{ $place->informations['barman_exclusivity'] ? 'sim' : 'não' }}</span></li>
                                  <li class="list-group-item">Exclusividade Música<span class="badge badge-success m-l-5">{{ $place->informations['music_exclusivity'] ? 'sim' : 'não' }}</span></li>
                              </ul>
                             <!-- / Time -->

                            </div>
                        </div>
                    </div>
                    <!-- / Extra Informations -->
                </div>
                <!-- / RIGHT COL -->

                <!-- Promotions -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Promoções</h2>
                        </div>
                    </div>
                </div>
                <!-- / Promotions -->

                <!-- Map -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Mapa</h2>
                        </div>
                    </div>
                </div>
                <!-- / Map -->

                <!-- Tour Virtual -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Tour Virtual</h2>
                        </div>
                    </div>
                </div>
                <!-- / Tour Virtual -->

                <!-- Videos -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Vídeos</h2>
                        </div>
                    </div>
                </div>
                <!-- / Videos -->

                <!-- Decorations -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Decorações exclusivas</h2>
                        </div>
                    </div>
                </div>
                <!-- / Decorations -->

                <!-- Anuncios -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Anuncios</h2>
                        </div>
                    </div>
                </div>
                <!-- / Anuncios -->
            </div>
        </div>
    </div>
</section>

@stop

@section('scripts')
	@parent

    <script>
        $('.btn-target').on('click', function(e) {
            var target = e.target.dataset.target
            $(e.target).addClass('out')
            $(target).addClass('in')
        })

        var coverSwiper = new Swiper($('.place-photos'), {
            prevButton: '.swiper-button-prev',
            nextButton: '.swiper-button-next'
        })
    </script>


@stop
