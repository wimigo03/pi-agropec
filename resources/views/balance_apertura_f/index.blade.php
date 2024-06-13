<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('balance_apertura_f.partials.search')
    @include('balance_apertura_f.partials.table')
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

        function cambiari(){
            var url = "{{ route('balance.apertura.index') }}";
            window.location.href = url;
        }

        function limpiar(){
            var url = "{{ route('balance.apertura.f.index') }}";
            window.location.href = url;
        }
    </script>
@stop
