<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>We Places</title>

  <!--|Google Font(Quicksand)|-->
  <link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <!--|Google Font(Roboto)|-->
  <link href='https://fonts.googleapis.com/css?family=Lato:400,100italic,100,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>


  <link href="{!! asset('build/landing/css/vendors.css') !!}" rel="stylesheet">

  <!--|Favicon|-->
  <link rel="icon" href="assets/images/favicon.ico">
  <!-- Touch Icons -->
  <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="assets/images/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="114x114" href="assets/images/apple-touch-icon-114x114.png">

</head>
<body>
<!--|Preloader|-->
<div class="preloader"></div>

<div id="section-1" class="brief">
  @include('landing.partials.navbar')
  @include('landing.partials.intro')
  @include('landing.partials.header')
  @include('landing.partials.parasuafesta')
  @include('landing.partials.video')
  @include('landing.partials.paraseuespaco')
  @include('landing.partials.screenshots')

</div> 

  @include('landing.partials.posts')
  @include('landing.partials.plans')
  @include('landing.partials.estatisticas')
  @include('landing.partials.download')
  @include('landing.partials.contato')
  @include('landing.partials.footer')


<!--|Scroll Top|-->
<a class="scroll-top" href="#intro"><img src="assets/images/map_icon.png"></a> <!--|End Scroll Top|-->


<script src="{!! asset('build/landing/js/vendors.js') !!}"></script>

</body>
</html>
