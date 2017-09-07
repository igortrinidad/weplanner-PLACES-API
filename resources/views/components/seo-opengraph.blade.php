
<!-- OPENGGRAPH -->
<?php 
	$routeName = \Request::route()->getName();
	$city = \Request::query('city');

	$current_url = \Request::fullUrl();

	$root_url = \Request::root();

?>
	
	<!-- GLOBAL -->
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<meta property="fb:app_id" content="151705885358217" />
	<meta property="og:type" content="website">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="We Places">
	<meta name="robots" content="index, follow">

<!-- LANDING INDEX -->
@if($routeName == 'landing.index')
	
	<title>We Places - O espaço de seu evento na sua mão!</title> 
    <meta name="description" content="We Places é uma ferramenta para facilitar a vida de quem procura espaços de cerimônia e festas."> 
	<meta property="og:url" content="https://weplaces.com.br">
	<meta property="og:title" content="We Places - O espaço de seu evento na sua mão!">
	<meta property="og:description" content="We Places é uma ferramenta para facilitar a vida de quem procura espaços de cerimônia e festas.">
	<meta property="og:image" content="https://blog.weplaces.com.br/wp-content/uploads/2017/07/banner-promocional.png">
	<meta property="og:image:type" content="image/png">

@endif

@if($routeName == 'search')

	<script type="application/ld+json">
		{
		  "@context": "http://schema.org",
		  "@type": "WebSite",
		  "url": "https://weplaces.com.br/",
		  "potentialAction": {
		    "@type": "SearchAction",
		    "target": "https://weplaces.com.br/buscar?q={search_term_string}",
		    "query-input": "required name=search_term_string"
		  }
		}

	</script>

	@if( !$city )

		<title>We Places - O espaço de seu evento na sua mão!</title> 
	    <meta name="description" content="We Places é uma ferramenta para facilitar a vida de quem procura espaços de cerimônia e festas."> 
		<meta property="og:url" content="https://weplaces.com.br/buscar">
		<meta property="og:title" content="We Places - O espaço de seu evento na sua mão!">
		<meta property="og:description" content="We Places é uma ferramenta para facilitar a vida de quem procura espaços de cerimônia e festas.">
		<meta property="og:image" content="https://blog.weplaces.com.br/wp-content/uploads/2017/07/banner-promocional.png">
		<meta property="og:image:type" content="image/png">

	@else

		<title>Espaços de festa em {{$city}}</title> 
	    <meta name="description" content="No We Places você encontrará espaços de festa e cerimônia em {{$city}}.">

		<meta property="og:image" content="https://blog.weplaces.com.br/wp-content/uploads/2017/07/banner-promocional.png">
		<meta property="og:image:type" content="image/png">
		<meta property="og:url" content="{{ $current_url }}">
		<meta property="og:title" content="Espaços de festa em {{$city}}.">
		<meta property="og:description" content="No We Places você encontrará espaços de festa e cerimônia em {{$city}}.">

			<script type="application/ld+json">
				{
				  	"@context": "http://schema.org",
				  	"@type": "WebSite",
				  	"name": "We Places",
				  	"alternateName": "Espaços de festa em {{$city}}",
				  	"url": "https://weplaces.com.br"
				}
			</script>
	@endif
@endif

<!-- LANDING COMPANIES SHOW -->
@if($routeName == 'places.show')

	<title>{{$place->name}} no We Places</title> 
	<meta name="description" content="Conheça o espaço {{$place->name}} no We Places.">

	<meta property="og:url" content="{{ $current_url }}">
	<meta property="og:description" content="We Places é uma ferramenta para facilitar a vida de quem procura espaços de cerimônia e festas.">
	<meta property="og:image" content="{{$place->avatar}}">
	<meta property="og:title" content="{{$place->name}} no We Places">
	<meta property="og:image:type" content="image/png">


@endif
