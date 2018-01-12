<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="cache-control" content="public">
    <meta http-equiv="expires" content ="<?php echo date('l jS \of F Y h:i:s A', strtotime('+7 days')); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
    <link rel="shortcut icon" href="/icons/icon_p.png" type="image/x-icon"/>

  @include('components.seo-opengraph')

  <!--|Google Font(Quicksand)|-->
  <link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <!--|Google Font(Roboto)|-->
  <link href='https://fonts.googleapis.com/css?family=Lato:400,100italic,100,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
  <!-- Styles -->
  <link href="{!! elixir('build/landing/css/vendors.css') !!}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/css/swiper.min.css">

  <style media="screen">

    .cover {
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
    }
    .cover-fixed { background-attachment: fixed; }
    .wrapper {
        background-attachment: fixed;
        width: 100%; height: auto;
        position: relative;
        padding: 50px 0;
    }
    .wrapper-title {
        background: rgba(255, 255, 255, .8);
        padding: 5px 10px;
        margin: 0 auto 10px auto;
        border-radius: 4px;
        font-size: 2.5rem;
        color: #4a5464;
    }
    .card .cover {
        width: 100%; height: 150px;
        border-radius: 4px;
    }

    /* Buttons */
    .btn.btn-info {
        background-color: #69a7be !important;
        border-color: #69a7be !important;
    }
    .btn.btn-facebook {
        background-color: #4267b2 !important;
        border-color: #4267b2 !important;
        color: #fff;
    }
    .btn.btn-whatsapp {
        background-color: #1ebea5 !important;
        border-color: #1ebea5 !important;
        color: #fff;
    }
    .btn:focus { outline: none; }
    /*CARD */
    .card {
        position: relative;
        background: #F2F2F2;
        box-shadow: 0 0 5px rgba(42,42,42,.3);
        margin-bottom: 30px;
        border-radius: 4px;
        padding: 4px;
        font-weight: 400;
    }
    .card .card-header {
        position: relative;
        border-radius: 4px;
    }

    @media screen and (min-width: 768px) {
        .card .card-header:not(.ch-alt) {
            padding: 23px 25px;
        }
    }

    @media screen and (max-width: 991px) {
        .card .card-header:not(.ch-alt) {
            padding: 18px;
        }
    }

    .card .card-header h2 {
        margin: 0;
        line-height: 100%;
        font-size: 17px;
        font-weight: 400;
    }

    .card .card-header h2 small {
        display: block;
        margin-top: 8px;
        color: #AEAEAE;
        line-height: 160%;
    }

    @media screen and (min-width: 768px) {
        .card .card-header.ch-alt {
            padding: 23px 26px;
        }
    }

    @media screen and (max-width: 991px) {
        .card .card-header.ch-alt {
            padding: 18px 18px 28px;
        }
    }

    .card .card-header.ch-alt:not([class*="bgm-"]) {
        background-color: #FBFBFB;
    }

    .card .card-header[class*="bgm-"] h2,
    .card .card-header[class*="bgm-"] h2 small {
        color: #fff;
    }

    .card .card-header .actions {
        position: absolute;
        right: 10px;
        z-index: 2;
        top: 15px;
    }

    .card .card-header .btn-float {
        right: 25px;
        bottom: -23px;
        z-index: 1;
    }

    @media screen and (min-width: 768px) {
        .card .card-body.card-padding {
            padding: 23px 22px;
        }
    }

    @media screen and (max-width: 991px) {
        .card .card-body.card-padding {
            padding: 14px;
        }
    }

    .card .card-body.card-padding-sm {
        padding: 11px;
    }

    .card-header:not(.ch-alt):not([class*="bgm-"]) + .card-padding {
        padding-top: 0;
    }
    /* / CARD */
  </style>

  <title>We Places</title>

  <!--|Favicon|-->
  <link rel="icon" href="assets/images/favicon.ico">
  <!-- Touch Icons -->
  <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="assets/images/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="114x114" href="assets/images/apple-touch-icon-114x114.png">

  <!-- Hotjar Tracking Code for https://weplaces.com.br -->
  <script>
      (function(h,o,t,j,a,r){
          h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
          h._hjSettings={hjid:578278,hjsv:5};
          a=o.getElementsByTagName('head')[0];
          r=o.createElement('script');r.async=1;
          r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
          a.appendChild(r);
      })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
  </script>

</head>
<body class="sticky-nav">

<div id="section-1" class="brief">
    @include('landing.companies.navbar')
</div>

    @section('content')
    @show

    @include('landing.partials.footer')

<!--|Scroll Top|-->
<a class="scroll-top" href="#section-1"><img src="/assets/images/map_icon.png"></a> <!--|End Scroll Top|-->


<script src="{!! elixir('build/landing/js/vendors.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.jquery.min.js"></script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-70761422-6', 'auto');
    ga('send', 'pageview');

</script>

    @section('scripts')
    @show

</body>
</html>
