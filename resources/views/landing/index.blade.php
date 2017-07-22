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
  <!--|Icon Font(Ionicons)|-->
  <link href='assets/css/ionicons.min.css' rel='stylesheet' type='text/css'>
  <!--|Animated|-->
  <link href='assets/css/animate.css' rel='stylesheet' type='text/css'>
  <!--|Owl Carousel|-->
  <link href='assets/css/owl.carousel.css' rel='stylesheet' type='text/css'>
  <!--|Magnific Popup|-->
  <link href='assets/css/magnific-popup.css' rel='stylesheet' type='text/css'>
  <!--|Bootstrap|-->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">

  <!--|Site Stylesheet|-->
  <link href="assets/css/style.css" rel="stylesheet" type="text/css"> <!--//Default Color(Blue)-->
  <link href="assets/css/general.css" rel="stylesheet" type="text/css"> <!--//Default Color(Blue)-->

  <!--|Favicon|-->
  <link rel="icon" href="assets/images/favicon.ico">
  <!-- Touch Icons -->
  <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="assets/images/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="114x114" href="assets/images/apple-touch-icon-114x114.png">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="assets/js/html5shiv.min.js"></script>
  <script src="assets/js/respond.min.js"></script>
  <![endif]-->
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

<!--|===================================================================
/ Javascript
/=======================================================================|-->
<!--|jQuery|-->
<script src="assets/js/jquery.min.js"></script>
<!--|Wow|-->
<script src="assets/js/wow.min.js"></script>
<!--|Waypoints|-->
<script src="assets/js/waypoints.min.js"></script>
<!--|CounterUp|-->
<script src="assets/js/jquery.counterup.min.js"></script>
<!--|Owl Carousel|-->
<script src="assets/js/owl.carousel.min.js"></script>
<!--|Magnific Popup|-->
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<!--|Form|-->
<script src="assets/js/jquery.form.js"></script>
<!--|Validate|-->
<script src="assets/js/jquery.validate.min.js"></script>
<!--|ajaxchimp|-->
<script src="assets/js/jquery.ajaxchimp.min.js"></script>
<!--|Bootstrap|-->
<script src="assets/js/bootstrap.min.js"></script>
<!--|Init|-->
<script src="assets/js/init.js"></script>
</body>
</html>
