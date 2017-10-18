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

    /* Map */
    #mapShow { height: 100%; }

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

                        <div class="card-body p-10 text-center">

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
                                <div class="m-t-10">
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

                            <button type="button" class="btn btn-xs btn-block btn-facebook m-t-10 p-5 f-15" @click="openShareFacebook()">
                                <i class="ion-social-facebook m-r-5" ></i>Compartilhar no facebook
                            </button>
                            <button type="button" class="btn btn-xs btn-block btn-whatsapp m-t-10 p-5 f-15" @click="openShareWhatsapp()">
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
                        <div class="card-body p-10">
                            <p class="text-justify">{!! $place->description !!}</p>
                        </div>
                    </div>
                    <!-- / Description -->

                    <!-- Workdays -->
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2>Dias e Horários</h2>
                        </div>
                        <div class="card-body p-10 text-center">
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
                            <li class="list-group-item">Mínimo de convidados <span class="badge badge-primary m-l-5">{{ $place->min_guests }}</span></li>
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
                           <li class="list-group-item">Possui horário limite ? <span class="badge badge-primary m-l-5">{{ $place->informations['time_limit'] ? 'sim' : 'não' }}</span></li>
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
                        <!-- Promotion -->
                        @foreach($place->poromotions as $promotion)
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="card">
                                    <div class="card-header ch-alt text-left">
                                        <span class="date">
                                            <strong>
                                                Promoção para:
                                                <small>{{ $promotion->date->format('d/m/Y') }}</small>
                                            </strong>
                                        </span>
                                        <span class="discount">
                                            <span class="badge badge-black-opacity">
                                                - {{ $promotion->discount }}<small>%</small>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="card-body p-10">

                                        <h2 class="title text-overflow">{{ $place->name }}</h2>
                                        <span>
                                            <i class="fa fa-map-marker"></i>
                                            {{ $place->city }} - {{ $place->state }}
                                        </span>

                                        <div class="m-t-10 m-b-10">
                                            <div class="values">
                                                <span class="from-value">
                                                    De:
                                                    <small class="value">{{ money_format($promotion->orginal_value) }}</small>
                                                </span>
                                                <span class="to-value">
                                                    Por:
                                                    <strong>{{ money_format($promotion->value) }}</strong>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="m-t-20">
                                            @if($promotion->is_reserved)
                                                <p>Você quase conseguiu!</p>
                                                <span class="label label f-13 p-5 f-300 label label-danger block m-b-5">Reservado</span>

                                            @else
                                                <span class="label label f-13 p-5 f-300 label label-success block m-b-5">Disponível para reserva</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- / Promotion -->
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
                        <div class="card-body p-t-5">
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
                        <div class="card-body p-t-5" style="height: 400px;">
                            <div id="mapShow" height="400px"></div>
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
                        <div class="card-body p-t-5">
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
                        <div class="card-body p-t-5">
                            Listar Videos
                        </div>
                    </div>
                </div>
                @endif
                <!-- / Videos -->

                <!-- Decorations -->
                @if(count($place->decorations) > 0)
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="m-0">Decorações assinadas</h2>
                        </div>
                        <div class="card-body p-t-5">
                            <span>Este local não possui decorações assinadas</span>
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
                        <div class="card-body p-t-5">
                            <span>Listar</span>
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
            mounted: function() {
            },
            methods: {
                openShareFacebook: function(){
                    let that = this
                    
                    var url = `https://www.facebook.com/dialog/share?app_id=151705885358217&href=https://weplaces.com.br/espacos/{{ $place->slug }}&display=popup&mobile_iframe=true`;
                    window.open(url, '_blank', 'location=yes');
                    
                },

                openShareWhatsapp: function(){
                    let that = this
                
                    var url = `https://api.whatsapp.com/send?text=Confira o espaço {{ $place->name }} no weplaces, veja o abaixo: https://weplaces.com.br/espacos/{{ $place->slug }}`;
                    window.open(url, '_system', null);
                },
            },
        });


        var mapStyle = [
            {
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dfdfdf"
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#523735"
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#c9b2a6"
                    }
                ]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#dcd2be"
                    }
                ]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#ae9e90"
                    }
                ]
            },
            {
                "featureType": "landscape.natural",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dfdfdf"
                    }
                ]
            },
            {
                featureType: 'poi',
                stylers: [{visibility: 'off'}]
            },
            {
                featureType: 'transit',
                elementType: 'labels.icon',
                stylers: [{visibility: 'off'}]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#fdfcf8"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f8c967"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#e9bc62"
                    }
                ]
            },
            {
                "featureType": "road.highway.controlled_access",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e98d58"
                    }
                ]
            },
            {
                "featureType": "road.highway.controlled_access",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#db8555"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#806b63"
                    }
                ]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dfdfdf"
                    }
                ]
            },
            {
                "featureType": "transit.line",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#8f7d77"
                    }
                ]
            },
            {
                "featureType": "transit.line",
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#dfdfdf"
                    }
                ]
            },
            {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dfdfdf"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#b9d3c2"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#92998d"
                    }
                ]
            }
        ];

        function initMap() {
            var myLatLng = {lat: {{ $place->address['geolocation']['lat'] }}, lng: {{ $place->address['geolocation']['lng'] }} };
            var contentString =
                '<div id="content">'+
                    '<div id="siteNotice">'+
                    '</div>'+
                    '<h1 id="firstHeading" class="firstHeading">{{$place->name}}</h1>'+
                    '<div id="bodyContent" style="font-size: 11px;">'+
                        '<p><b>Endereço:</b> {{$place->address['full_address']}}</p>'+
                    '</div>'+
                '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 200
            });
            var map = new google.maps.Map(document.getElementById('mapShow'), {
                zoom: 16,
                center: myLatLng,
                styles: mapStyle
            });
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: '/assets/images/map_icon.png',
                title: '{{ $place->name }}'
            });
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
        }

        var coverSwiper = new Swiper($('.place-photos'), {
            prevButton: '.swiper-button-prev',
            nextButton: '.swiper-button-next'
        })

        $(document).ready(function(){
            jQuery("#gallery").unitegallery({
                tiles_type:"justified"
            });
        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&callback=initMap"></script>


@stop
