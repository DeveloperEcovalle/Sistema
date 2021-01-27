<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ecovalle | Sistema de Producción</title>

    <link href="{{asset('Inspinia/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('Inspinia/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('Inspinia/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('Inspinia/css/style.css')}}" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <p class="">Buen dia, se adjunta la cotización CO-0{{$cotizacion->id}}</p>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    Atte.{{$cotizacion->empresa->razon_social}}<br>
                </div>
            </div>
        </div>
    </div>

</body>

</html>