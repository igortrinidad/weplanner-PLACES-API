<style media="screen">
.search-container {
    max-width: 767px;
    margin: 0 auto;
}

.input-group {
    position: relative;
    width: 100%;
}
.input-search {
    position: relative;
    z-index: 1;
    padding: 20px 25px !important;
}

.form-control:focus {
    outline: none !important;
    border-color: #ccc !important;
    box-shadow: none !important;
}

.btn-search {
    position: absolute;
    top: 0; right: -2px;
    border-radius: 0px 4px 4px 0px;
    z-index: 10;
    text-transform: none;
}
</style>
<div class="search-container">
    <form method="GET" action="/buscar">
        <div class="input-group">
            <input class="form-control input-search" id="autocomplete" placeholder="Informe a cidade" />
            <input type="hidden" name="city" id="city" value="">
            <input type="hidden" name="lat" id="lat" value="">
            <input type="hidden" name="lng" id="lng" value="">
            <button type="submit" class="btn btn-info btn-search">Buscar</button>
        </div>
    </form>
</div>


    @section('scripts')
        @parent

        <script>

          function initAutocomplete() {


            var autocomplete = new google.maps.places.Autocomplete(
            (
                document.getElementById('autocomplete')), {
              types: ['(cities)'],
              language: 'pt-BR',
              componentRestrictions: {'country': 'br'}
            });


            autocomplete.addListener('place_changed', function() {
              var place = autocomplete.getPlace();
                if (place.geometry) {

                    var city = document.getElementById('city');
                    city.setAttribute('value', place.name)

                    var lat = document.getElementById('lat');
                    lat.setAttribute('value', place.geometry.location.lat())

                    var lng = document.getElementById('lng');
                    lng.setAttribute('value', place.geometry.location.lng())

                } else {
                    document.getElementById('autocomplete').placeholder = 'Enter a city';
                }

            });
          }


        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
             async defer></script>

    @stop
