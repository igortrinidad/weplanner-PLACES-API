<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%; margin:0 auto; padding:0; width:100%" height="100%"
      width="100%">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
</head>
<body width="100%" style="height:100%; margin:0; padding:0; width:100%" height="100%">

<div class="hover-container" width="100%" style="height:100%; margin:0; padding:0; width:100%" height="100%"
>
   <span class="hover-edit-container">
   <i class="zmdi zmdi-edit"></i>
   </span>
    <table width="100%" border="0" cellspacing="0" cellpadding="30" background=""
           style="background-color: rgb(241, 243, 244); color: rgb(85, 85, 85);">
        <tbody>
        <tr>
            <td>
                <center>
                    <p><br></p>

                    <!-- INIT MAIN -->
                    <table width="540px" cellspacing="0" border="0" align="center">
                        <tbody>

                        <!-- HEADER LOGO WE PLANNER -->
                        <tr>
                            <td style="padding: 50px 50px 50px 50px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85); "
                                align="center">
                                <img src="https://s3.amazonaws.com/weplanner-places-assets/logos/LOGO-1-04.png"
                                     width="200" alt="Logo" border="0" style="-ms-interpolation-mode:bicubic">
                            </td>
                        </tr>

                        <!-- TITULO -->
                        @if(isset($data['messageTitle']) && !empty($data['messageTitle']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <span style="font-size: 16px;">
                                    {!! $data['messageTitle'] !!}
                                </span>
                            </td>
                        </tr>
                        @endif

                        <!-- MESSAGE TWO -->
                        @if(isset($data['messageOne']) && !empty($data['messageOne']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: center; ">
                                    <span style="font-size: 14px;">
                                        {!! $data['messageOne'] !!}
                                    </span>
                                </p>
                            </td>
                        </tr>
                        @endif

                        <!-- MESSAGE THREE -->
                        @if(isset($data['messageTwo']) && !empty($data['messageTwo']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: center; ">
                                    <span style="font-size: 14px;">
                                        {!! $data['messageTwo'] !!}
                                    </span>
                                </p>
                            </td>
                        </tr>
                        @endif

                        <!-- MESSAGE THREE -->
                        @if(isset($data['messageThree']) && !empty($data['messageThree']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: center; ">
                                    <span style="font-size: 14px;">
                                        {!! $data['messageThree'] !!}
                                    </span>
                                </p>
                            </td>
                        </tr>
                        @endif

                        <!-- MESSAGE THREE -->
                        @if(isset($data['access_code']) && !empty($data['access_code']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: center; ">
                                    <span style="font-size: 22px;">
                                        {!!$data['access_code'] !!}
                                    </span>
                                </p>
                            </td>
                        </tr>
                        @endif
                        
                        <!-- BOTÃO -->

                        @if(isset($data['button_link']) && !empty($data['button_link']))
                        <tr>
                            <td style="padding: 20px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255);"
                                align="center">
                                <center>
                                    <a style="transition: all 100ms ease-in; display: block; font-weight: bold; text-align: center; margin: 10px 10px 10px; text-decoration: none; max-width: 80% !important; background-color: rgb(255, 255, 255);"
                                       class="button-a" align="center"
                                       href="{{$data['button_link']}}">
                                        <span style="color: rgb(255, 255, 255); border-color: #69A7BE; background-color: #69A7BE; width: 200px; height: 70px; border-radius: 5px; border-width: 5px; font-size: 20px; padding: 15px 30px 15px 30px; margin: 30px 10px;">
                                            {{{$data['button_name']}}}
                                        </span>
                                    </a>
                                </center>
                            </td>
                        </tr>
                        @endif

                        <!-- ULTIMO BLOCO -->
                        @if(isset($data['messageFour']) && !empty($data['messageFour']))
                        <tr>
                            <td style="padding: 20px 20px 40px 20px; text-align: center; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: center; "><span style="font-size: 14px;">
                                    {!! $data['messageFour'] !!}
                                </span>
                                </p>

                            </td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                    <!-- INIT FOOTER WEPLANNER -->
                    <table 
                        cellspacing="0" 
                        cellpadding="0" 
                        border="0" 
                        align="center" 
                        width="540px"
                        style="mso-table-lspace:0; mso-table-rspace:0; border-collapse:collapse; border-spacing:0; margin:0 auto; table-layout:fixed; max-width:100%; background-color: #EDECEC"
                        role="presentation">
                        <tbody>
                            <tr>
                                <td style="mso-table-lspace:0; mso-table-rspace:0; color:#888; font-family:sans-serif; line-height:18px; mso-height-rule:exactly; padding:30px 20px 30px 20px; text-align:center; width:100%; color: #2B343A; "
                                    align="center" width="100%">
                                    <hr>
                                    <span style="font-size: 12px; font-style: italic; font-weight: 80;">
                                        <p>
                                            © 2017 We-Planner Soluções Tecnológicas - <a href="https://places.we-planner.com">Places We-planner</a>
                                        </p>
                                        <P>
                                            SE VOCÊ RECEBEU ESTA COMUNICAÇÃO POR ENGANO, por favor, não a encaminhe a ninguém (pode conter informação confidencial ou privilegiada), por favor, apague todas as cópias da mensagem, incluindo todos anexos e notifique o remetente imediatamente. Obrigado por sua cooperação. Os termos acima reflectem um possível acordo comercial, são fornecidos exclusivamente como base para desenvolvimentos futuros e não se destinam a ser, nem constituem qualquer obrigação juridicamente vinculativa. Nenhuma obrigação juridicamente vinculativa deverá ser criada, pressuposta ou inferida até que haja um acordo final assinado por escrito por todas as partes envolvidas.
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