<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('caja_venta.partials.search')
    @include('caja_venta.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('#sucursal_id').select2({
                theme: "bootstrap4",
                placeholder: "--Sucursales--",
                width: '100%'
            });

            $('#user_id').select2({
                theme: "bootstrap4",
                placeholder: "--Asignado a--",
                width: '100%'
            });

            $('#user_asignado_id').select2({
                theme: "bootstrap4",
                placeholder: "--Asignado por--",
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

            var cleave = new Cleave('#monto', {
                numeral: true,
                numeralDecimalScale: 2,
                rawValueTrimPrefix: true
            });
        });

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                search();
                event.preventDefault();
            }
        });

        function create(){
            var id = $("#empresa_id").val()
            var url = "{{ route('caja.venta.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function search(){
            var id = $("#empresa_id").val();
            var url = "{{ route('caja.venta.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('caja.venta.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop

