<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%; margin:0 auto; padding:0; width:100%" height="100%"
      width="100%">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
</head>
<body width="100%" style="height:100%; margin:0; padding:0; width:100%" height="100%">

<div class="hover-container" width="100%" style="height:100%; margin:0; padding:0; width:100%" height="100%"
>
   <span class="hover-edit-container">
   <i class="zmdi zmdi-edit"></i>
   </span>
    <table width="100%" border="0" cellspacing="0" cellpadding="30"
           style="background-color: rgb(241, 243, 244); color: rgb(85, 85, 85);">
        <tbody>
        <tr>
            <td>
                <center>
                    <p><br></p>

                    <!-- INIT MAIN -->
                    <table width="440px" cellspacing="0" border="0" align="center">
                        <tbody>

                        <!-- HEADER LOGO WE PLANNER -->
                        <tr>
                            <td style="padding: 0; text-align: center; max-width: 100% !important; background-color: rgb(123, 204, 198); color: rgb(85, 85, 85); "
                                align="center">
                                <img
                                    width="100%"
                                    src="https://blog.weplaces.com.br/wp-content/uploads/2017/07/weekly_1.png"
                                    alt="We Places Cabeçalho"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0; text-align: center; max-width: 100% !important; background-color: #75CDC7; color: rgb(242, 242, 242); "
                                align="center">
                                <h2>{{$data['place']['name']}}</h2>
                                <span>Mês {{\Carbon\Carbon::now()->format('m/Y')}}</span>
                            </td>

                        </tr>
                        <tr>

                            <td style="padding: 0; text-align: center; max-width: 100% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85); "
                                align="center">
                                <img
                                    width="100%"
                                    src="https://blog.weplaces.com.br/wp-content/uploads/2017/07/weekly_2.png"
                                    alt="We Places Cabeçalho"
                                />
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <!-- MAIN CONTENT   -->
                    <table
                        cellspacing="0"
                        cellpadding="0"
                        align="center"
                        width="440px"
                        role="presentation"
                        style="background-color: #fff; padding: 30px 0;"
                    >
                        <tbody style="text-align: center; font-family: 'Lato', sans-serif;">


                            @if(!$data['place']['has_owner'])
                            <tr>
                                <td colspan="2" style="padding: 15px 0;  width: 50%;">
                                    <span style="font-size: 22px;">{{$data['reservation_interests']}} pessoas</span>
                                    <h5 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">gostariam de reservar seu espaço</h5>
                                </td>
                            </tr>
                            @endif

                            @if($data['place']['has_owner'])
                                <!--Reservations-->
                                <tr style="width: 100%;">
                                    <td colspan="2" style="padding: 15px 0; border-top: 1px solid rgb(242, 242, 242); border-bottom: 1px solid rgb(242, 242, 242); width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Reservas</h4>
                                    </td>
                                </tr>

                                <tr style="width: 100%;">
                                    <td style="padding: 15px 0; width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Total</h4>
                                        <span style="font-size: 22px;">{{$data['reservations']['total']['last_month']}}</span>
                                        <br />
                                        <small>
                                            @if($data['reservations']['total']['stats']['no_data'])
                                                Sem dados do
                                            @endif
                                            @if(!$data['reservations']['total']['stats']['value'] > 0 && !$data['reservations']['total']['stats']['no_data'])
                                                Igual ao
                                            @endif
                                            @if($data['reservations']['total']['stats']['is_positive'] && $data['reservations']['total']['stats']['value'])
                                                <span style="color:green"><strong>{{$data['reservations']['total']['stats']['value']}}%</strong></span> mais que no
                                            @endif
                                            @if(!$data['reservations']['total']['stats']['is_positive'] && $data['reservations']['total']['stats']['value'])
                                                <span style=" color:red"><strong>{{$data['reservations']['total']['stats']['value']}}%</strong></span> menos que no
                                            @endif
                                            <br>
                                            mês anterior
                                        </small>
                                    </td>

                                    <td style="padding: 15px 0; width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Confirmadas</h4>
                                        <span style="font-size: 22px;">{{$data['reservations']['confirmed']['last_month']}}</span>
                                        <br />
                                        <small>
                                            @if($data['reservations']['confirmed']['stats']['no_data'])
                                                Sem dados do
                                            @endif
                                            @if(!$data['reservations']['confirmed']['stats']['value'] > 0 && !$data['reservations']['confirmed']['stats']['no_data'])
                                                Igual ao
                                            @endif
                                            @if($data['reservations']['confirmed']['stats']['is_positive'] && $data['reservations']['confirmed']['stats']['value'])
                                                <span style="color:green"><strong>{{$data['reservations']['confirmed']['stats']['value']}}%</strong></span> mais que no
                                            @endif
                                            @if(!$data['reservations']['confirmed']['stats']['is_positive'] && $data['reservations']['confirmed']['stats']['value'])
                                                <span style=" color:red"><strong>{{$data['reservations']['confirmed']['stats']['value']}}%</strong></span> menos que no
                                            @endif
                                            <br>
                                            mês anterior
                                        </small>
                                    </td>
                                </tr>

                                <tr style="width: 100%;">
                                    <td style="padding: 15px 0; width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Canceladas</h4>
                                        <span style="font-size: 22px;">{{$data['reservations']['canceled']['last_month']}}</span>
                                        <br />
                                        <small>
                                            @if($data['reservations']['canceled']['stats']['no_data'])
                                                Sem dados do
                                            @endif
                                            @if(!$data['reservations']['canceled']['stats']['value'] > 0 && !$data['reservations']['canceled']['stats']['no_data'])
                                                Igual ao
                                            @endif
                                            @if($data['reservations']['canceled']['stats']['is_positive'] && $data['reservations']['canceled']['stats']['value'])
                                                <span style="color:green"><strong>{{$data['reservations']['canceled']['stats']['value']}}%</strong></span> mais que no
                                            @endif
                                            @if(!$data['reservations']['canceled']['stats']['is_positive'] && $data['reservations']['canceled']['stats']['value'])
                                                <span style=" color:red"><strong>{{$data['reservations']['canceled']['stats']['value']}}%</strong></span> menos que no
                                            @endif
                                            <br>
                                            mês anterior
                                        </small>
                                    </td>

                                    <td style="padding: 15px 0; width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Aguardando</h4>
                                        <span style="font-size: 22px;">{{$data['reservations']['waiting']['last_month']}}</span>
                                        <br />
                                        <small>
                                            @if($data['reservations']['waiting']['stats']['no_data'])
                                                Sem dados do
                                            @endif
                                            @if(!$data['reservations']['waiting']['stats']['value'] > 0 && !$data['reservations']['waiting']['stats']['no_data'])
                                                Igual ao
                                            @endif
                                            @if($data['reservations']['waiting']['stats']['is_positive'] && $data['reservations']['waiting']['stats']['value'])
                                                <span style="color:green"><strong>{{$data['reservations']['waiting']['stats']['value']}}%</strong></span> mais que no
                                            @endif
                                            @if(!$data['reservations']['waiting']['stats']['is_positive'] && $data['reservations']['waiting']['stats']['value'])
                                                <span style=" color:red"><strong>{{$data['reservations']['waiting']['stats']['value']}}%</strong></span> menos que no
                                            @endif
                                            <br>
                                            mês anterior
                                        </small>
                                    </td>

                                </tr>
                                <!--Reservations-->

                                <!-- Pre reservations-->
                                <tr style="width: 100%;">
                                    <td colspan="2" style="padding: 15px 0; border-top: 1px solid rgb(242, 242, 242); border-bottom: 1px solid rgb(242, 242, 242); width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Pré reservas</h4>
                                    </td>
                                </tr>

                                <tr style="width: 100%;">
                                    <td style="padding: 15px 0; width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Total</h4>
                                        <span style="font-size: 22px;">{{$data['pre_reservations']['total']['last_month']}}</span>
                                        <br />
                                        <small>
                                            @if($data['pre_reservations']['total']['stats']['no_data'])
                                                Sem dados do
                                            @endif
                                            @if(!$data['pre_reservations']['total']['stats']['value'] > 0 && !$data['pre_reservations']['total']['stats']['no_data'])
                                                Igual ao
                                            @endif
                                            @if($data['pre_reservations']['total']['stats']['is_positive'] && $data['pre_reservations']['total']['stats']['value'])
                                                <span style="color:green"><strong>{{$data['pre_reservations']['total']['stats']['value']}}%</strong></span> mais que no
                                            @endif
                                            @if(!$data['pre_reservations']['total']['stats']['is_positive'] && $data['pre_reservations']['total']['stats']['value'])
                                                <span style=" color:red"><strong>{{$data['pre_reservations']['total']['stats']['value']}}%</strong></span> menos que no
                                            @endif
                                            <br>
                                            mês anterior
                                        </small>
                                    </td>

                                    <td style="padding: 15px 0; width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Confirmadas</h4>
                                        <span style="font-size: 22px;">{{$data['pre_reservations']['confirmed']['last_month']}}</span>
                                        <br />
                                        <small>
                                            @if($data['pre_reservations']['confirmed']['stats']['no_data'])
                                                Sem dados do
                                            @endif
                                            @if(!$data['pre_reservations']['confirmed']['stats']['value'] > 0 && !$data['pre_reservations']['confirmed']['stats']['no_data'])
                                                Igual ao
                                            @endif
                                            @if($data['pre_reservations']['confirmed']['stats']['is_positive'] && $data['pre_reservations']['confirmed']['stats']['value'])
                                                <span style="color:green"><strong>{{$data['pre_reservations']['confirmed']['stats']['value']}}%</strong></span> mais que no
                                            @endif
                                            @if(!$data['pre_reservations']['confirmed']['stats']['is_positive'] && $data['pre_reservations']['confirmed']['stats']['value'])
                                                <span style=" color:red"><strong>{{$data['pre_reservations']['confirmed']['stats']['value']}}%</strong></span> menos que no
                                            @endif
                                            <br>
                                            mês anterior
                                        </small>
                                    </td>
                                </tr>

                                <tr style="width: 100%;">
                                    <td style="padding: 15px 0; width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Canceladas</h4>
                                        <span style="font-size: 22px;">{{$data['pre_reservations']['canceled']['last_month']}}</span>
                                        <br />
                                        <small>
                                            @if($data['pre_reservations']['canceled']['stats']['no_data'])
                                                Sem dados do
                                            @endif
                                            @if(!$data['pre_reservations']['canceled']['stats']['value'] > 0 && !$data['pre_reservations']['canceled']['stats']['no_data'])
                                                Igual ao
                                            @endif
                                            @if($data['pre_reservations']['canceled']['stats']['is_positive'] && $data['pre_reservations']['canceled']['stats']['value'])
                                                <span style="color:green"><strong>{{$data['pre_reservations']['canceled']['stats']['value']}}%</strong></span> mais que no
                                            @endif
                                            @if(!$data['pre_reservations']['canceled']['stats']['is_positive'] && $data['pre_reservations']['canceled']['stats']['value'])
                                                <span style=" color:red"><strong>{{$data['pre_reservations']['canceled']['stats']['value']}}%</strong></span> menos que no
                                            @endif
                                            <br>
                                            mês anterior
                                        </small>
                                    </td>

                                    <td style="padding: 15px 0; width: 50%;">
                                        <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Aguardando</h4>
                                        <span style="font-size: 22px;">{{$data['pre_reservations']['waiting']['last_month']}}</span>
                                        <br />
                                        <small>
                                            @if($data['pre_reservations']['waiting']['stats']['no_data'])
                                                Sem dados do
                                            @endif
                                            @if(!$data['pre_reservations']['waiting']['stats']['value'] > 0 && !$data['pre_reservations']['waiting']['stats']['no_data'])
                                                Igual ao
                                            @endif
                                            @if($data['pre_reservations']['waiting']['stats']['is_positive'] && $data['pre_reservations']['waiting']['stats']['value'])
                                                <span style="color:green"><strong>{{$data['pre_reservations']['waiting']['stats']['value']}}%</strong></span> mais que no
                                            @endif
                                            @if(!$data['pre_reservations']['waiting']['stats']['is_positive'] && $data['pre_reservations']['waiting']['stats']['value'])
                                                <span style=" color:red"><strong>{{$data['pre_reservations']['waiting']['stats']['value']}}%</strong></span> menos que no
                                            @endif
                                            <br>
                                            mês anterior
                                        </small>
                                    </td>

                                </tr>
                                <!--Pre reservations-->
                            @endif

                            <!--Tracking-->
                            <tr style="width: 100%;">
                                <td colspan="2" style="padding: 15px 0; border-top: 1px solid rgb(242, 242, 242); width: 50%;">
                                    <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Dados coletados</h4>
                                </td>
                            </tr>

                            <tr style="width: 100%;">
                                <td colspan="2" style="padding: 15px 0; border-top: 1px solid rgb(242, 242, 242); border-bottom: 1px solid rgb(242, 242, 242); width: 50%;">
                                    <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Visualizações</h4>
                                    <span style="font-size: 22px;">{{$data['views']['last_month']}}</span>
                                    <br />
                                    <small>
                                        @if($data['views']['stats']['no_data'])
                                            Sem dados do
                                        @endif
                                        @if(!$data['views']['stats']['value'] > 0 && !$data['views']['stats']['no_data'])
                                            Igual ao
                                        @endif
                                        @if($data['views']['stats']['is_positive'] && $data['views']['stats']['value'])
                                            <span style="color:green"><strong>{{$data['views']['stats']['value']}}%</strong></span> mais que no
                                        @endif
                                        @if(!$data['views']['stats']['is_positive'] && $data['views']['stats']['value'])
                                            <span style=" color:red"><strong>{{$data['views']['stats']['value']}}%</strong></span> menos que no
                                        @endif
                                        <br>
                                        mês anterior
                                    </small>
                                </td>
                            </tr>

                            <tr style="width: 100%;">
                                <td style="padding: 15px 0; width: 50%;">
                                    <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Tempo de Visualização</h4>
                                    <span style="font-size: 22px;">{{$data['permanence']['last_month']}}</span>
                                    <br />
                                    <small>
                                        @if($data['permanence']['stats']['no_data'])
                                            Sem dados do
                                        @endif
                                        @if(!$data['permanence']['stats']['value'] > 0 && !$data['permanence']['stats']['no_data'])
                                            Igual ao
                                        @endif
                                        @if($data['permanence']['stats']['is_positive'] && $data['permanence']['stats']['value'])
                                            <span style="color:green"><strong>{{$data['permanence']['stats']['value']}}%</strong></span> mais que no
                                        @endif
                                        @if(!$data['permanence']['stats']['is_positive'] && $data['permanence']['stats']['value'])
                                            <span style=" color:red"><strong>{{$data['permanence']['stats']['value']}}%</strong></span> menos que no
                                        @endif
                                        <br>
                                        mês anterior
                                    </small>
                                </td>

                                <td style="padding: 15px 0; border-left: 1px solid rgb(242, 242, 242); width: 50%;">
                                    <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Contato <small>(whatsapp)</small></h4>
                                    <span style="font-size: 22px;">{{$data['whatsapp_clicks']['last_month']}}</span>
                                    <br />
                                    <small>
                                        @if($data['whatsapp_clicks']['stats']['no_data'])
                                            Sem dados do
                                        @endif
                                        @if(!$data['whatsapp_clicks']['stats']['value'] > 0 && !$data['whatsapp_clicks']['stats']['no_data'])
                                            Igual ao
                                        @endif
                                        @if($data['whatsapp_clicks']['stats']['is_positive'] && $data['whatsapp_clicks']['stats']['value'])
                                            <span style="color:green"><strong>{{$data['whatsapp_clicks']['stats']['value']}}%</strong></span> mais que no
                                        @endif
                                        @if(!$data['whatsapp_clicks']['stats']['is_positive'] && $data['whatsapp_clicks']['stats']['value'])
                                            <span style=" color:red"><strong>{{$data['whatsapp_clicks']['stats']['value']}}%</strong></span> menos que no
                                        @endif
                                        <br>
                                        mês anterior
                                    </small>
                                </td>

                            </tr>

                            <tr style="width: 100%;">
                                <td style="padding: 15px 0; border-top: 1px solid rgb(242, 242, 242); width: 50%;">
                                    <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Contato <small>(ligação)</small></h4>
                                    <span style="font-size: 22px;">{{$data['call_clicks']['last_month']}}</span>
                                    <br />
                                    <small>
                                        @if($data['call_clicks']['stats']['no_data'])
                                            Sem dados do
                                        @endif
                                        @if(!$data['call_clicks']['stats']['value'] > 0 && !$data['call_clicks']['stats']['no_data'])
                                            Igual ao
                                        @endif
                                        @if($data['call_clicks']['stats']['is_positive'] && $data['call_clicks']['stats']['value'])
                                            <span style="color:green"><strong>{{$data['call_clicks']['stats']['value']}}%</strong></span> mais que no
                                        @endif
                                        @if(!$data['call_clicks']['stats']['is_positive'] && $data['call_clicks']['stats']['value'])
                                            <span style=" color:red"><strong>{{$data['call_clicks']['stats']['value']}}%</strong></span> menos que no
                                        @endif
                                        <br>
                                        mês anterior
                                    </small>
                                </td>

                                <td style="padding: 15px 0; border-top: 1px solid rgb(242, 242, 242); border-left: 1px solid rgb(242, 242, 242); width: 50%;">
                                    <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important;">Contato <small>(Mensagem)</small></h4>
                                    <span style="font-size: 22px;">{{$data['contact_clicks']['last_month']}}</span>
                                    <br />
                                    <small>
                                        @if($data['contact_clicks']['stats']['no_data'])
                                            Sem dados do
                                        @endif
                                        @if(!$data['contact_clicks']['stats']['value'] > 0 && !$data['contact_clicks']['stats']['no_data'])
                                            Igual ao
                                        @endif
                                        @if($data['contact_clicks']['stats']['is_positive'] && $data['contact_clicks']['stats']['value'])
                                            <span style="color:green"><strong>{{$data['contact_clicks']['stats']['value']}}%</strong></span> mais que no
                                        @endif
                                        @if(!$data['contact_clicks']['stats']['is_positive'] && $data['contact_clicks']['stats']['value'])
                                            <span style=" color:red"><strong>{{$data['contact_clicks']['stats']['value']}}%</strong></span> menos que no
                                        @endif
                                        <br>
                                        mês anterior
                                    </small>
                                </td>
                            </tr>
                            <!--Tracking-->

                        </tbody>
                    </table>
                    <!-- /MAIN CONTENT -->

                    <!-- INIT INTERACTIONS -->
                    <table
                        cellspacing="0"
                        cellpadding="0"
                        align="center"
                        width="440px"
                        role="presentation"
                        style="background-color: #fff; padding: 30px;"
                    >
                        <tbody style="font-family: 'Lato', sans-serif;">
                            <tr>
                                <td style="padding: 5px 0; background-color: rgb(255, 255, 255); width: 60%;">
                                    <h2 style="font-size: 20px; text-transform: uppercase; font-family: 'Quicksand', sans-serif; margin: 0 !important;">Compartilhamentos</h2>
                                </td>
                                <td style="background-color: rgb(255, 255, 255); width: 40%;">
                                </td>
                            </tr>
                            <tr style="width: 100%">
                                <td style="padding: 15px; border-radius:4px 0px 0px 4px; background-color: rgb(242, 242, 242); width: 60%; border: 1px solid rgb(255, 255, 255)">
                                    <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important; color: #3b5998; ">Facebook</h4>
                                </td>
                                <td style="padding: 15px; border-radius:0px 4px 4px 0px; background-color: rgb(242, 242, 242); border-left: 1px solid rgb(255, 255, 255); border-top: 1px solid rgb(255, 255, 255); width: 40%;">
                                    <span><strong>{{$data['facebook_shares']['last_month']}}</strong></span>
                                    <br />
                                    <small>
                                        @if($data['facebook_shares']['stats']['no_data'])
                                            Sem dados do
                                        @endif
                                        @if(!$data['facebook_shares']['stats']['value'] > 0 && !$data['facebook_shares']['stats']['no_data'])
                                            Igual ao
                                        @endif
                                        @if($data['facebook_shares']['stats']['is_positive'] && $data['facebook_shares']['stats']['value'])
                                            <span style="color:green"><strong>{{$data['facebook_shares']['stats']['value']}}%</strong></span> mais que no
                                        @endif
                                        @if(!$data['facebook_shares']['stats']['is_positive'] && $data['facebook_shares']['stats']['value'])
                                            <span style=" color:red"><strong>{{$data['facebook_shares']['stats']['value']}}%</strong></span> menos que no
                                        @endif
                                        <br>
                                        mês anterior
                                    </small>
                                </td>
                            </tr>
                            <tr style="width: 100%">
                                <td style="padding: 15px; border-radius:4px 0px 0px 4px; background-color: rgb(242, 242, 242); width: 60%; border: 1px solid rgb(255, 255, 255)">
                                    <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important; color: #009688; ">Whatsapp</h4>
                                </td>
                                <td style="padding: 15px; border-radius:0px 4px 4px 0px; background-color: rgb(242, 242, 242); border-left: 1px solid rgb(255, 255, 255); border-top: 1px solid rgb(255, 255, 255); width: 40%;">
                                    <span><strong>{{$data['whatsapp_shares']['last_month']}}</strong></span>
                                    <br />
                                    <small>
                                        @if($data['whatsapp_shares']['stats']['no_data'])
                                            Sem dados do
                                        @endif
                                        @if(!$data['whatsapp_shares']['stats']['value'] > 0 && !$data['whatsapp_shares']['stats']['no_data'])
                                            Igual ao
                                        @endif
                                        @if($data['whatsapp_shares']['stats']['is_positive'] && $data['whatsapp_shares']['stats']['value'])
                                            <span style="color:green"><strong>{{$data['whatsapp_shares']['stats']['value']}}%</strong></span> mais que no
                                        @endif
                                        @if(!$data['whatsapp_shares']['stats']['is_positive'] && $data['whatsapp_shares']['stats']['value'])
                                            <span style=" color:red"><strong>{{$data['whatsapp_shares']['stats']['value']}}%</strong></span> menos que no
                                        @endif
                                        <br>
                                        mês anterior
                                    </small>
                                </td>
                            </tr>
                            <tr style="width: 100%">
                                <td style="padding: 15px; border-radius:4px 0px 0px 4px; background-color: rgb(242, 242, 242); width: 60%; border: 1px solid rgb(255, 255, 255)">
                                    <h4 style="font-family: 'Quicksand', sans-serif; margin: 0 !important; color: #999;">Link copiados</h4>
                                </td>
                                <td style="padding: 15px; border-radius:0px 4px 4px 0px; background-color: rgb(242, 242, 242); border-left: 1px solid rgb(255, 255, 255); border-top: 1px solid rgb(255, 255, 255); width: 40%;">
                                    <span><strong>{{$data['link_shares']['last_month']}}</strong></span>
                                    <br />
                                    <small>
                                        @if($data['link_shares']['stats']['no_data'])
                                            Sem dados do
                                        @endif
                                        @if(!$data['link_shares']['stats']['value'] > 0 && !$data['link_shares']['stats']['no_data'])
                                            Igual ao
                                        @endif
                                        @if($data['link_shares']['stats']['is_positive'] && $data['link_shares']['stats']['value'])
                                            <span style="color:green"><strong>{{$data['link_shares']['stats']['value']}}%</strong></span> mais que no
                                        @endif
                                        @if(!$data['link_shares']['stats']['is_positive'] && $data['link_shares']['stats']['value'])
                                            <span style=" color:red"><strong>{{$data['link_shares']['stats']['value']}}%</strong></span> menos que no
                                        @endif
                                        <br>
                                        mês anterior
                                    </small>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <!-- INIT FOOTER WEPLANNER -->
                    <table
                        cellspacing="0"
                        cellpadding="0"
                        border="0"
                        align="center"
                        width="440px"
                        style="mso-table-lspace:0; mso-table-rspace:0; border-collapse:collapse; border-spacing:0; margin:0 auto; table-layout:fixed; max-width:100%; background-color: #EDECEC"
                        role="presentation">
                        <tbody>
                            <tr>
                                <td style="mso-table-lspace:0; mso-table-rspace:0; color:#888; font-family:sans-serif; line-height:18px; mso-height-rule:exactly; padding:30px 20px 30px 20px; text-align:center; width:100%; color: #2B343A; "
                                    align="center" width="100%">
                                    <hr>
                                    <span style="font-size: 12px; font-style: italic; font-weight: 80;">
                                        <p>
                                            © 2017 We-Planner Soluções Tecnológicas - <a href="https://weplaces.com.br">We Places</a>
                                        </p>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- END FOOTER WEPLANNER -->
                </center>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
