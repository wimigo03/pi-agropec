<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('balance_apertura.partials.search')
    @include('balance_apertura.partials.table')
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

        function search(){

        }

        function cambiarf(){
            var url = "{{ route('balance.apertura.f.index') }}";
            window.location.href = url;
        }

        function limpiar(){
            var url = "{{ route('balance.apertura.index') }}";
            window.location.href = url;
        }

        function create(){
            var url = "{{ route('balance.apertura.create') }}";
            window.location.href = url;
        }
    </script>
@stop
