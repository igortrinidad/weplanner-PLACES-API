<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WePlaces - Informativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <style media="screen">
        * { padding: 0; margin: 0; box-sizing: border-box; }
    </style>
</head>

<body style="font-family: 'Lato', sans-serif; background-color: #fff; color: #2c3e50;">

    <header style="text-align: center; padding-bottom: 30px;">
        <div style="max-width: 700px; margin: 0 auto; padding: 0 10px;">
            <div style="clear: both;">
                <img
                    width="100%"
                    src="https://blog.weplaces.com.br/wp-content/uploads/2017/07/Header_Email_Weekly.png"
                    alt="We Places Cabeçalho"
                />
            </div>
            <div style="width: 100%; display:block;">
                <h1 style="font-family: 'Quicksand', sans-serif; font-weight: 400;">{{$data['place']['name']}}</h1>
                <span>{{\Carbon\Carbon::now()->format('d/m/Y')}}</span>
            </div>
        </div>
    </header>


    <main>
        <div style="max-width: 700px; margin: 0 auto; padding: 30px 10px;">
            <div style="clear: both;">
                <div style="width: 50%; display:block; float: left;  text-align: center;">
                    <div style="border-right: 1px solid #f2f2f2; border-bottom: 1px solid #f2f2f2; width: 100%; padding: 25px; text-align: center;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 400;">Visualizações do mês</h2>
                        <span style="font-size: 20px;"><strong>{{$data['views']['last_month']}}</strong></span>
                        <br />
                        <span style="margin-top: 10px; text-transform: uppercase; font-weight: 700; font-family: 'Quicksand', sans-serif; font-size: 12px;">
                            @if($data['views']['stats']['no_data'])
                                Sem dados do mês anterior
                            @endif
                            @if(!$data['views']['stats']['value'] >= 0 && !$data['views']['stats']['no_data'])
                                Igual ao mês anterior
                            @endif
                            @if($data['views']['stats']['is_positive'] && $data['views']['stats']['value'])
                            <span style="color:green"><strong>{{$data['views']['stats']['value']}}%</strong></span>  a mais que no mês anterior
                            @endif
                            @if(!$data['views']['stats']['is_positive'] && $data['views']['stats']['value'])
                            <span style=" color:red"><strong>{{$data['views']['stats']['value']}}%</strong></span>  a menos que no mês anterior
                            @endif
                        </span>
                    </div>
                </div>

                <div style="width: 50%; display:block; float: left;  text-align: center;">
                    <div style="border-left: 1px solid #f2f2f2; border-bottom: 1px solid #f2f2f2; width: 100%; padding: 25px;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 400;">Tempo de Permanência</h2>
                        <span style="font-size: 20px;"><strong>{{$data['permanence']['last_month']}}</strong></span>
                        <br />
                        <span style="margin-top: 10px; text-transform: uppercase; font-weight: 700; font-family: 'Quicksand', sans-serif; font-size: 12px;">
                            @if($data['permanence']['stats']['no_data'])
                                Sem dados do mês anterior
                            @endif
                            @if(!$data['permanence']['stats']['value'] >= 0 && !$data['permanence']['stats']['no_data'])
                                Igual ao mês anterior
                            @endif
                            @if($data['permanence']['stats']['is_positive'] && $data['permanence']['stats']['value'])
                                <span style="color:green"><strong>{{$data['permanence']['stats']['value']}}%</strong></span>  a mais que no mês anterior
                            @endif
                            @if(!$data['permanence']['stats']['is_positive'] && $data['permanence']['stats']['value'])
                                <span style=" color:red"><strong>{{$data['permanence']['stats']['value']}}%</strong></span>  a menos que no mês anterior
                            @endif
                        </span>
                    </div>
                </div>

                <div style="width: 50%; display:block; float: left;  text-align: center;">
                    <div style="border-right: 1px solid #f2f2f2; border-top: 1px solid #f2f2f2; width: 100%; padding: 25px;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 400;">Clicks no contato <small style="font-size: 12px;">(Whatsapp)</small></h2>
                        <span style="font-size: 20px;"><strong>{{$data['whatsapp_clicks']['last_month']}}</strong></span>
                        <br />
                        <span style="margin-top: 10px; text-transform: uppercase; font-weight: 700; font-family: 'Quicksand', sans-serif; font-size: 12px;">
                            @if($data['whatsapp_clicks']['stats']['no_data'])
                                Sem dados do mês anterior
                            @endif
                            @if(!$data['whatsapp_clicks']['stats']['value'] >= 0 && !$data['whatsapp_clicks']['stats']['no_data'])
                                Igual ao mês anterior
                            @endif
                            @if($data['whatsapp_clicks']['stats']['is_positive'] && $data['whatsapp_clicks']['stats']['value'])
                                <span style="color:green"><strong>{{$data['whatsapp_clicks']['stats']['value']}}%</strong></span>  a mais que no mês anterior
                            @endif
                            @if(!$data['whatsapp_clicks']['stats']['is_positive'] && $data['whatsapp_clicks']['stats']['value'])
                                <span style=" color:red"><strong>{{$data['whatsapp_clicks']['stats']['value']}}%</strong></span>  a menos que no mês anterior
                            @endif
                        </span>
                    </div>
                </div>

                <div style="width: 50%; display:block; float: left;  text-align: center;">
                    <div style="border-left: 1px solid #f2f2f2; border-top: 1px solid #f2f2f2; width: 100%; padding: 25px;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 400;">Clicks no contato <small style="font-size: 12px;">(Mensagem)</small></h2>
                        <span style="font-size: 20px;"><strong>{{$data['contact_clicks']['last_month']}}</strong></span>
                        <br />
                        <span style="margin-top: 10px; text-transform: uppercase; font-weight: 700; font-family: 'Quicksand', sans-serif; font-size: 12px;">
                            @if($data['contact_clicks']['stats']['no_data'])
                                Sem dados do mês anterior
                            @endif
                            @if(!$data['contact_clicks']['stats']['value'] >= 0 && !$data['contact_clicks']['stats']['no_data'])
                                Igual ao mês anterior
                            @endif
                            @if($data['contact_clicks']['stats']['is_positive'] && $data['contact_clicks']['stats']['value'])
                                <span style="color:green"><strong>{{$data['contact_clicks']['stats']['value']}}%</strong></span>  a mais que no mês anterior
                            @endif
                            @if(!$data['contact_clicks']['stats']['is_positive'] && $data['contact_clicks']['stats']['value'])
                                <span style=" color:red"><strong>{{$data['contact_clicks']['stats']['value']}}%</strong></span>  a menos que no mês anterior
                            @endif
                        </span>
                    </div>
                </div>

                <div style="width: 50%; display:block; float: left;  text-align: center;">
                    <div style="border-left: 1px solid #f2f2f2; border-top: 1px solid #f2f2f2; width: 100%; padding: 25px;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 400;">Clicks no contato <small style="font-size: 12px;">(Ligação)</small></h2>
                        <span style="font-size: 20px;"><strong>{{$data['call_clicks']['last_month']}}</strong></span>
                        <br />
                        <span style="margin-top: 10px; text-transform: uppercase; font-weight: 700; font-family: 'Quicksand', sans-serif; font-size: 12px;">
                            @if($data['call_clicks']['stats']['no_data'])
                                Sem dados do mês anterior
                            @endif
                            @if(!$data['call_clicks']['stats']['value'] >= 0 && !$data['call_clicks']['stats']['no_data'])
                                Igual ao mês anterior
                            @endif
                            @if($data['call_clicks']['stats']['is_positive'] && $data['call_clicks']['stats']['value'])
                                <span style="color:green"><strong>{{$data['call_clicks']['stats']['value']}}%</strong></span>  a mais que no mês anterior
                            @endif
                            @if(!$data['call_clicks']['stats']['is_positive'] && $data['call_clicks']['stats']['value'])
                                <span style=" color:red"><strong>{{$data['call_clicks']['stats']['value']}}%</strong></span>  a menos que no mês anterior
                            @endif
                        </span>
                    </div>
                </div>

                @if(!$data['place']['has_owner'])

                    <div style="width: 50%; display:block; float: left;  text-align: center;">
                        <div style="border-left: 1px solid #f2f2f2; border-top: 1px solid #f2f2f2; width: 100%; padding: 25px;">
                            <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 400;">Interesse em reservar</h2>
                            <span style="font-size: 20px;"><strong>{{$data['reservation_interests']}}</strong></span>
                        </div>
                    </div>
                @endif

                <!-- Compartilhamentos -->
                <div style="width: 100%; margin-top: 30px; display:block; float: left;">
                    <div style="width: 100%; padding: 10px;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 700; text-transform: uppercase;">Compartilhamentos</h2>
                    </div>
                </div>

                <div style="width: 80%; margin-top:5px; display:block; float: left;">
                    <div style="background: #f2f2f2; border-radius: 5px 0px 0px 5px; overflow: hidden; height: 60px; width: 100%; padding: 23px;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 700; text-transform:uppercase; font-size: 15px;">Compartilhamentos <small style="font-size: 12px;">Facebook</small></h2>
                    </div>
                </div>
                <div style="width: 20%; margin-top:5px; display:block; float: left;">
                    <div style="background: #f2f2f2; border-radius: 0px 5px 5px 0px; overflow: hidden; height: 60px; border-left: 2px solid #fff; width: 100%; padding: 17px;">
                        <span style="font-size: 12px;"><strong>{{$data['facebook_shares']['last_month']}}</strong></span>
                        <br />
                        <span style="margin-top: 10px; text-transform: uppercase; font-weight: 700; font-family: 'Quicksand', sans-serif; font-size: 10px;">
                            @if($data['facebook_shares']['stats']['no_data'])
                                Sem dados do mês anterior
                            @endif
                            @if(!$data['facebook_shares']['stats']['value'] >= 0 && !$data['facebook_shares']['stats']['no_data'])
                                Igual ao mês anterior
                            @endif
                            @if($data['facebook_shares']['stats']['is_positive'] && $data['facebook_shares']['stats']['value'])
                                <span style="color:green"><strong>{{$data['facebook_shares']['stats']['value']}}%</strong></span>  a mais que no mês anterior
                            @endif
                            @if(!$data['facebook_shares']['stats']['is_positive'] && $data['facebook_shares']['stats']['value'])
                                <span style=" color:red"><strong>{{$data['facebook_shares']['stats']['value']}}%</strong></span>  a menos que no mês anterior
                            @endif
                        </span>
                    </div>
                </div>

                <div style="width: 80%; margin-top:5px; display:block; float: left;">
                    <div style="background: #f2f2f2; border-radius: 5px 0px 0px 5px; overflow: hidden; height: 60px; width: 100%; padding: 23px;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 700; text-transform:uppercase; font-size: 15px;">Compartilhamentos <small style="font-size: 12px;">Whatsapp</small></h2>
                    </div>
                </div>
                <div style="width: 20%; margin-top:5px; display:block; float: left;">
                    <div style="background: #f2f2f2; border-radius: 0px 5px 5px 0px; overflow: hidden; height: 60px; border-left: 2px solid #fff; width: 100%; padding: 17px;">
                        <span style="font-size: 12px;"><strong>{{$data['whatsapp_shares']['last_month']}}</strong></span>
                        <br />
                        <span style="margin-top: 10px; text-transform: uppercase; font-weight: 700; font-family: 'Quicksand', sans-serif; font-size: 10px;">
                            @if($data['whatsapp_shares']['stats']['no_data'])
                                Sem dados do mês anterior
                            @endif
                            @if(!$data['whatsapp_shares']['stats']['value'] >= 0 && !$data['whatsapp_shares']['stats']['no_data'])
                                Igual ao mês anterior
                            @endif
                            @if($data['whatsapp_shares']['stats']['is_positive'] && $data['whatsapp_shares']['stats']['value'])
                                <span style="color:green"><strong>{{$data['whatsapp_shares']['stats']['value']}}%</strong></span>  a mais que no mês anterior
                            @endif
                            @if(!$data['whatsapp_shares']['stats']['is_positive'] && $data['whatsapp_shares']['stats']['value'])
                                <span style=" color:red"><strong>{{$data['whatsapp_shares']['stats']['value']}}%</strong></span>  a menos que no mês anterior
                            @endif
                        </span>
                    </div>
                </div>

                <div style="width: 80%; margin-top:5px; display:block; float: left;">
                    <div style="background: #f2f2f2; border-radius: 5px 0px 0px 5px; overflow: hidden; height: 60px; width: 100%; padding: 23px;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 700; text-transform:uppercase; font-size: 15px;">Compartilhamentos <small style="font-size: 12px;">Link</small></h2>
                    </div>
                </div>
                <div style="width: 20%; margin-top:5px; display:block; float: left;">
                    <div style="background: #f2f2f2; border-radius: 0px 5px 5px 0px; overflow: hidden; height: 60px; border-left: 2px solid #fff; width: 100%; padding: 17px;">
                        <span style="font-size: 12px;"><strong>{{$data['link_shares']['last_month']}}</strong></span>
                        <br />
                        <span style="margin-top: 10px; text-transform: uppercase; font-weight: 700; font-family: 'Quicksand', sans-serif; font-size: 10px;">
                            @if($data['link_shares']['stats']['no_data'])
                                Sem dados do mês anterior
                            @endif
                            @if(!$data['link_shares']['stats']['value'] >= 0 && !$data['link_shares']['stats']['no_data'])
                                Igual ao mês anterior
                            @endif
                            @if($data['link_shares']['stats']['is_positive'] && $data['link_shares']['stats']['value'])
                                <span style="color:green"><strong>{{$data['link_shares']['stats']['value']}}%</strong></span>  a mais que no mês anterior
                            @endif
                            @if(!$data['link_shares']['stats']['is_positive'] && $data['link_shares']['stats']['value'])
                                <span style=" color:red"><strong>{{$data['link_shares']['stats']['value']}}%</strong></span>  a menos que no mês anterior
                            @endif
                        </span>
                    </div>
                </div>
                <!-- /Compartilhamentos  -->

            </div>
        </div>
    </main>

    <footer style="clear:both; padding: 30px 0 0 0; text-align: center;">
        <div style="max-width: 700px; margin: 0 auto;">
            <div style="margin: 0 -10px;">
                <div style="padding: 0 10px;">
                    <div style="background: #f2f2f2; border-radius: 5px; padding: 20px 0">
                        <span style="font-size: 10px">
                            Não quer receber mais e-mails como esse? <a href="#" style="color: #7bccc6; text-decoration: none;">Desinscreva-se aqui.</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
