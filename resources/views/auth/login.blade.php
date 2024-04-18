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
            height: 100vh;
            margin: 0;
        }

        .contenedor {
            width: 400px; /* Ajusta el ancho según tus necesidades */
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="card card-custom">
                <div class="card-header font-verdana-bg">
                    <b>ACCESO A USUARIO</b>
                </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1">
                                        <i class="fa-solid fa-user"></i>
                                      </span>
                                    </div>
                                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Usuario" aria-label="Usuario" aria-describedby="basic-addon1">
                                  </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon2">
                                        <i class="fa-solid fa-lock"></i>
                                      </span>
                                    </div>
                                    <input type="password" name="password" class="form-control" placeholder="Contraseña" aria-label="password" aria-describedby="basic-addon2">
                                  </div>
                            </div>
                        </div>
                        <div>
                            <button type=submit class="btn btn-block btn-flat btn-primary">
                                <span class="fas fa-sign-in-alt"></span>&nbsp;Ingresar
                            </button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    <script src="{{ asset('js/dashboard/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/bootstrap.min.js') }}"></script>
</body>
</html>
