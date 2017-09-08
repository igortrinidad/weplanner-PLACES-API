<style media="screen">
    .section {
        color: #4a5464;
    }
    .swiper-container.place-photos {
        height: 350px !important;
        margin-top: -40px;
    }
    .place-cover {
        width: 100%; height: 300px !important;
    }
    .place-content {
        width: 100%;
        position: relative;
    }
    /* List */
    .list-group-item.title {
        background-color: #f2f2f2;
    }

    .picture-circle {
        box-sizing: border-box;
        margin: 0 auto;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        border-radius: 50%;
        width: 100px;
        height: 100px;
    }

    /* Badges */
    .badge.badge-primary { background-color: #7BCCC6; color: #FFFFFF; }
    .badge.badge-success { background-color: #82CB7D; color: #FFFFFF; }
    .badge.badge-danger  { background-color: #ED7461; color: #FFFFFF; }
    .badge.badge-warning { background-color: #FFD397; color: #FFFFFF; }


</style>
@extends('landing.companies.index')

@section('content')

<section class="section" id="show">

    <div class="place-photos">
        <div class="cover place-cover" style="background-image: url('{{ $place->avatar }}')"></div>
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
                                    <div class="picture-circle" style="background-image: url('{{ $photo->photo_url }}')"></div>
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
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-xs btn-block p-5 f-15"
                                        v-show="!interactions.displayPhoneNumber"
                                        @click="interactions.displayPhoneNumber = !interactions.displayPhoneNumber"
                                    >
                                        Mostrar telefone
                                    </button>
                                    <div class="info" v-show="interactions.displayPhoneNumber">
                                        <i class="ion-ios-telephone m-r-5"></i>
                                        <span class="f-16"><a href="tel:{{ $place->phone }}">{{ $place->phone }}</a></span>
                                    </div>
                                </div>
                            @endif
                            <!-- Phone -->

                            <!-- Website -->
                            @if($place->website)
                                <div class="m-t-5">
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-xs btn-block p-5 f-15"
                                        v-show="!interactions.displayWebsite"
                                        @click="interactions.displayWebsite = !interactions.displayWebsite"
                                    >
                                        Mostrar Website
                                    </button>
                                    <div class="info" v-show="interactions.displayWebsite">
                                        <i class="ion-ios-world-outline m-r-5"></i>
                                        <span class="f-16"><a href="{{ $place->website }}" target="_blank">{{ $place->website }}</a></span>
                                    </div>
                                </div>
                            @endif
                            <!-- Website -->

                            <button type="button" class="btn btn-xs btn-block btn-facebook m-t-5 p-5 f-15" @click="openShareFacebook()">
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
                            <p class="text-justify">{!! $place->description !!}</p>
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
                        <div class="card-header ch-alt text-center">
                            <h2>Informações</h2>
                        </div>
                    </div>
                    <div class="m-b-20">

                        <!-- Capacidade -->
                        <ul class="list-group">
                            <li class="list-group-item title"><strong>Capacidade do Local</strong></li>
                            <li class="list-group-item">Mínimo de convidade <span class="badge badge-primary m-l-5">{{ $place->min_guests }}</span></li>
                            <li class="list-group-item">Máximo de convidados <span class="badge badge-primary m-l-5">{{ $place->max_guests }}</span></li>
                        </ul>
                        <!-- /Capacidade -->

                        <!-- Servicos -->
                        <ul class="list-group">
                            <li class="list-group-item title"><strong>Serviços</strong></li>
                            <li class="list-group-item">
                                Cerimônia <span class="badge m-l-5 {{ $place->cerimony ? 'badge-success' : 'badge-danger' }}">
                                    {{ $place->cerimony ? 'sim' : 'não' }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                Festa / Recepção <span class="badge m-l-5 {{ $place->party_space ? 'badge-success' : 'badge-danger' }}">
                                    {{ $place->party_space ? 'sim' : 'não' }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                Acessibilidade <span class="badge m-l-5 {{ $place->accessibility ? 'badge-success' : 'badge-danger' }}">
                                    {{ $place->accessibility ? 'sim' : 'não' }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                Estacionamento <span class="badge m-l-5 {{ $place->parking ? 'badge-success' : 'badge-danger' }}">
                                    {{ $place->parking ? 'sim' : 'não' }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                Espaço coberto <span class="badge m-l-5 {{ $place->covered ? 'badge-success' : 'badge-danger' }}">
                                    {{ $place->covered ? 'sim' : 'não' }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                Espaço ao ar livre <span class="badge m-l-5 {{ $place->outdoor ? 'badge-success' : 'badge-danger' }}">
                                    {{ $place->outdoor ? 'sim' : 'não' }}
                                </span>
                            </li>
                        </ul>
                       <!-- /Servicos -->

                       <!-- Time -->
                       <ul class="list-group">
                           <li class="list-group-item title"><strong>Horário</strong></li>
                           <li class="list-group-item">Possui horário limete ? <span class="badge badge-primary m-l-5">{{ $place->informations['time_limit'] ? 'sim' : 'não' }}</span></li>
                           @if($place->informations['time_limit'])
                                <li class="list-group-item">Horário até <span class="badge badge-primary m-l-5">{{ $place->informations['time_limit_value'] }}</span></li>
                           @endif
                       </ul>
                      <!-- / Time -->

                      <!-- Time -->
                      <ul class="list-group">
                          <li class="list-group-item title"><strong>Exclusividade de fornecedores</strong></li>
                          <li class="list-group-item">
                              Exclusividade Buffet <span class="badge m-l-5 {{ $place->informations['buffet_exclusivity'] ? 'badge-success' : 'badge-danger' }}">
                                  {{ $place->informations['buffet_exclusivity'] ? 'sim' : 'não' }}
                              </span>
                          </li>
                          <li class="list-group-item">
                              Exclusividade Decoração<span class="badge m-l-5 {{ $place->informations['decoration_exclusivity'] ? 'badge-success' : 'badge-danger' }}">
                                  {{ $place->informations['decoration_exclusivity'] ? 'sim' : 'não' }}
                              </span>
                          </li>
                          <li class="list-group-item">
                              Exclusividade Barman<span class="badge m-l-5 {{ $place->informations['barman_exclusivity'] ? 'badge-success' : 'badge-danger' }}">
                                  {{ $place->informations['barman_exclusivity'] ? 'sim' : 'não' }}
                              </span>
                          </li>
                          <li class="list-group-item">
                              Exclusividade Música<span class="badge m-l-5 {{ $place->informations['music_exclusivity'] ? 'badge-success' : 'badge-danger' }}">
                                  {{ $place->informations['music_exclusivity'] ? 'sim' : 'não' }}
                              </span>
                          </li>
                      </ul>
                     <!-- / Time -->

                    </div>
                    <!-- / Extra Informations -->
                </div>
                <!-- / RIGHT COL -->

                <!-- Promotions -->
                @if($place->promotions)
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Promoções</h2>
                        </div>
                        <div class="card-body card-padding">
                            Listar Promoções
                        </div>
                    </div>
                </div>
                @endif
                <!-- / Promotions -->

                <!-- PHOTOS -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Fotos</h2>
                        </div>
                        <div class="card-body card-padding">
                            <div id="gallery" style="display:none;">
                                @foreach($place->photos as $photo)
                                <img alt="{{$place->name}}" src="{{$photo->photo_url}}"
                                    data-image="{{$photo->photo_url}}"
                                    data-description="{{$place->name}}">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                @if($place->address['geolocation'])
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Mapa</h2>
                        </div>
                        <div class="card-body card-padding">
                            Exibir mapa
                        </div>
                    </div>
                </div>
                @endif
                <!-- / Map -->

                <!-- Tour Virtual -->
                @if($place->virtual_tour_url)
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Tour Virtual</h2>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-primary">Exibir tour virtual</button>
                            <iframe src="{{ $place->virtual_tour_url }}" width="100%" height="500"></iframe>
                        </div>
                    </div>
                </div>
                @endif
                <!-- / Tour Virtual -->

                <!-- Videos -->
                @if(count($place->videos) > 0)
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Vídeos</h2>
                        </div>
                        <div class="card-body card-padding">
                            Listar Videos
                        </div>
                    </div>
                </div>
                @endif
                <!-- / Videos -->

                <!-- Decorations -->
                @if($place->decorations)
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Decorações exclusivas</h2>
                        </div>
                        <div class="card-body card-padding">
                            Listar Decorações
                        </div>
                    </div>
                </div>
                @endif
                <!-- / Decorations -->

                <!-- Anuncios
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Anuncios</h2>
                        </div>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
</section>

@stop

@section('scripts')
	@parent
    <script src="https://npmcdn.com/vue/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#show',
            data: {
                interactions: {
                    displayPhoneNumber: false, displayWebsite: false
                }
            },
            mounted() {
            },
            methods: {
            },
        });

        $(document).ready(function(){
            jQuery("#gallery").unitegallery({
                tiles_type:"justified"
            });
        });
    </script>


@stop
