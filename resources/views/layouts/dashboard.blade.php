<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pi-Resto | Home</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="{{ asset('fonts/roboto.css') }}" rel="stylesheet">
    <link href="{{ asset('fonts/poppins.css') }}" rel="stylesheet">
    <link href="{{ asset('fonts/source.css') }}" rel="stylesheet">
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
    <link href="{{ asset('js/datepicker/themes/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .linea-informacion {
            border-bottom: 1px solid #3498db;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        .text-bottom {
            position: relative;
        }
        .text-bottom b {
            position: absolute;
            bottom: 0;
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
            @include('layouts.partials.menu')
        </div>
    </aside>
    <main>
        <div class="site-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        @if (isset($empresa))
                            @include('layouts.partials.header')
                        @endif
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/dashboard/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/popper.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/main.js') }}"></script>
    <script src="{{ asset('js/lobibox/lobibox.js') }}"></script>
    {{--<script src="{{ asset('js/datepicker/datepicker.min.js') }}"></script>
    <script src="{{ asset('js/datepicker/datepicker.es.js') }}"></script>---}}
    <script src="{{ asset('js/datepicker/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/datepicker/datepicker-es.js') }}"></script>
    <script src="{{ asset('js/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('js/cleave/addons/cleave-phone.us.js') }}"></script>
    {{--<script
        src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"
        integrity="sha512-KaIyHb30iXTXfGyI9cyKFUIRSSuekJt6/vqXtyQKhQP6ozZEGY8nOtRS6fExqE4+RbYHus2yGyYg1BrqxzV6YA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    ></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/addons/cleave-phone.us.js"
        integrity="sha512-sYKXH+IAMtg7mVursFAH+Xu1mIvmSqTd8LTEhKdRmvJhtX2IKUFpkZBZ9pigORvIR6Nt5klEF/P+psiJRa6crQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    ></script>--}}
    {{--<script src="http://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}


    @yield('scripts')

    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/treeview/jstree.min.js') }}"></script>
    <script>
        /*function goToDashboardType(input){
            var url = "{{route('change',':tipo')}}"
            url = url.replace(':tipo', input.value);
            window.location.href = url;
        }*/
    </script>
  </body>
</html>
