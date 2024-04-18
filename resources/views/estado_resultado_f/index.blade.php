<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('estado_resultado_f.partials.search')
    @if ($show == '1')
        @include('estado_resultado_f.partials.table')
    @endif
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

            var cleave = new Cleave('#fecha_i', {
                date: true,
                datePattern: ['d', 'm', 'Y']
            });

            var cleave = new Cleave('#fecha_f', {
                date: true,
                datePattern: ['d', 'm', 'Y']
            });

            $("#fecha_i").datepicker({
                inline: false,
                dateFormat: "dd/mm/yy",
                autoClose: true,
            });

            $("#fecha_f").datepicker({
                inline: false,
                dateFormat: "dd/mm/yy",
                autoClose: true,
            });
        });

        function search(){
            var id = $("#empresa_id").val();
            var url = "{{ route('estado.resultado.f.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('estado.resultado.f.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function excel(){
            var id = $("#empresa_id").val();
            var url = "{{ route('estado.resultado.f.excel',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function pdf(){
            var id = $("#empresa_id").val();
            var url = "{{ route('estado.resultado.f.pdf',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function cambiari(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('estado.resultado.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
