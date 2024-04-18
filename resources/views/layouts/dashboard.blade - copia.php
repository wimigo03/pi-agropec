<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pi-Resto | Home</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Serif+Pro:400,600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/font-awesome_6.0/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles/font-verdana.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('css/treeview/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lobibox/lobibox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datepicker/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tooltips/tooltips.min.css') }}" rel="stylesheet">
    <style>
        .linea-informacion {
            /*border-bottom: 1px solid #3498db;*/
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
    </style>
    @yield('styles')
  </head>
  <body>
    @include('layouts.alerta-modal')
    @include('layouts.confirmar-modal')
    <aside class="sidebar">
        <div class="toggle">
            <a href="#" class="burger js-menu-toggle" data-toggle="collapse" data-target="#main-navbar">
                <span>
                    
                </span>
            </a>
        </div>
        <div class="side-inner">
            @include('layouts.partials.perfil') 
            @include('layouts.partials.subperfil') 
            @include('layouts.partials.menu') 
        </div>
    </aside>
    <main>
        <div class="site-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 linea-informacion font-verdana-bg">
                        <i class="fa-solid fa-user"></i> {{ auth()->user()->cargo_header }}
                    </div>
                    <div class="col-md-6 linea-informacion font-verdana-bg text-right">
                        <i class="fa-solid fa-id-card"></i> Perfil
                    </div>
                </div>
                @yield('content')
            </div>
        </div>  
    </main>
    
    <script src="{{ asset('js/dashboard/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/popper.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/main.js') }}"></script>
    <script src="{{ asset('js/lobibox/lobibox.js') }}"></script>
    <script src="{{ asset('js/datepicker/datepicker.min.js') }}"></script>
    <script src="{{ asset('js/datepicker/datepicker.es.js') }}"></script>

    @yield('scripts')
    
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/treeview/jstree.min.js') }}"></script>
    
  </body>
</html>