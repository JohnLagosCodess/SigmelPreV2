<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" href="/images/present_log_SIGMEL-17.png">
    <title>Sesión Expirada</title>

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            /* color: #B0BEC5; */
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 24px;
            margin-bottom: 40px;
        }

        .logo {
            line-height: .8 !important;
            max-height: 150px !important;
            width: auto !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="title">
                <img src="/images/present_log_SIGMEL-14.png" class="logo" alt="logo_sigmel">
                <br>
                Su sesión ha expirado por inactivdad en el sistema. Por favor inicie sesión nuevamente.
                <br><br>
                <a href="{{route('loginSigmel')}}" class="btn btn-info">Iniciar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>