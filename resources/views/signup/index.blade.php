<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta property="og:locale" content="pt_BR">
  <meta property="og:url" content="https://weplaces.com.br">
  <meta property="og:title" content="Cadastre se na We Places">
  <meta property="og:site_name" content="We Places">
  <meta property="og:description" content="We Places é uma ferramenta para facilitar a vida de quem procura espaços de cerimônia e festas, para os organizadores de eventos e para os administradores destes espaços.">
  <meta property="og:image" content="https://blog.weplaces.com.br/wp-content/uploads/2017/07/banner-promocional.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="800">
  <meta property="og:image:height" content="600">

  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>We Places</title>

  <!--|Google Font(Quicksand)|-->
  <link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <!--|Google Font(Roboto)|-->
  <link href='https://fonts.googleapis.com/css?family=Lato:400,100italic,100,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>


  <link href="{!! asset('build/landing/css/vendors.css') !!}" rel="stylesheet">

  <!--|Favicon|-->
  <link rel="icon" href="/assets/images/favicon.ico">
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
<body>

<div id="section-1" class="brief">
  @include('signup.navbar')

</div>

  @include('signup.form')



<script src="{!! asset('build/landing/js/vendors.js') !!}"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70761422-6', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>
