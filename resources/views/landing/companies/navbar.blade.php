<style media="screen">
    .fix-fixed {
        top: 0; position: fixed;
    }
</style>
<nav class="navbar fix-fixed">
    <div class="container">
        <!--|Navbar Header|-->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#primary-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <i class="ion-navicon"></i>
            </button>

            <!--|Site Brand|-->
            <a class="navbar-brand" href="/">
                <img src="https://blog.weplaces.com.br/wp-content/uploads/2017/07/LOGO-1-03.png" style="width: 110px;" alt="Logo We Places">
            </a>
        </div> <!--|End Navbar Header|-->

        <!--|Nav|-->
        <div class="collapse navbar-collapse" id="primary-nav">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/">Home</a></li>
                <li><a href="{{ route('search') }}">Buscar espaços</a></li>
                <li><a href="{{ route('promotions') }}">Promoções</a></li>
                <li><a href="https://app.weplaces.com.br" target="_blank">Acesse o app</a></li>
                <li><a href="https://blog.weplaces.com.br" target="_blank">Blog</a></li>
            </ul>
        </div><!--|End Nav|-->
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
            </div>
        </div>
    </div>
</nav>
