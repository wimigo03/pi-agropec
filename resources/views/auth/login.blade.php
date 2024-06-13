<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/dashboard/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome_6.0/css/all.css') }}" rel="stylesheet">
    <title>Login</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80vh;
            margin: 0;
        }

        .font-roboto-14 {font-size: 14px; font-family: "Roboto", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";}

        .imagen-pi-resto {
            width: 100px;
            height: auto;
            overflow: hidden;
            opacity: 0.3;
        }

        .card-custom {
            margin-top: 20px;
        }

        .abs-center {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="row abs-center">
            <div class="col-md-4">
                <img src="/images/pi-agropec.jpg" alt="pi-agropec" class="imagen-pi-resto">
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="card card-custom bg-light">
                        <div class="card-header font-roboto-14 text-center">
                            <b>
                                INGRESO AL SISTEMA
                            </b>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text font-roboto-14 bg-warning" id="basic-addon1">
                                                <i class="fa-solid fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="username" class="form-control font-roboto-14" value="{{ old('username') }}" placeholder="Usuario" aria-label="Usuario" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text font-roboto-14 bg-warning" id="basic-addon2">
                                                <i class="fa-solid fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="password" class="form-control font-roboto-14" placeholder="Contraseña" aria-label="password" aria-describedby="basic-addon2">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type=submit class="btn btn-block btn-outline-primary font-roboto-14">
                                        <span class="fas fa-sign-in-alt"></span>&nbsp;Iniciar sesion
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/dashboard/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/bootstrap.min.js') }}"></script>
</body>
</html>
