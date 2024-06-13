<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('clientes.partials.search')
    @include('clientes.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('#pais_id').select2({
                theme: "bootstrap4",
                placeholder: "--Pais--",
                width: '100%'
            });

            $('#estado').select2({
                theme: "bootstrap4",
                placeholder: "--Estado--",
                width: '100%'
            });

            var cleave = new Cleave('#fecha', {
                date: true,
                datePattern: ['d', 'm', 'Y']
            });

            $("#fecha").datepicker({
                inline: false,
                dateFormat: "dd/mm/yy",
                autoClose: true,
            });
        });

        function create(){
            $(".btn").hide();
            $(".spinner-btn").show();
            window.location.href = "{{ route('pi.clientes.create') }}";
        }

        function procesar(){
            var url = "{{ route('pi.clientes.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function limpiar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            window.location.href = "{{ route('pi.clientes.index') }}";
        }
    </script>
@stop
