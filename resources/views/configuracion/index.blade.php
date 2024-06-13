<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('precio_productos.partials.search')
    @include('configuracion.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%'
            });

            $('#estado').select2({
                theme: "bootstrap4",
                placeholder: "--Estado--",
                width: '100%'
            });
        });

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                search();
                event.preventDefault();
            }
        });

        function alerta(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function search(){
            var url = "{{ route('configuracion.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function limpiar(){
            var url = "{{ route('configuracion.index') }}";
            window.location.href = url;
        }

        function create(){
            var url = "{{ route('configuracion.create') }}";
            window.location.href = url;
        }
    </script>
@stop
