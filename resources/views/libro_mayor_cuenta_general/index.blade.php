<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @if (!isset($comprobantes))
        @include('libro_mayor_cuenta_general.partials.search')
    @endif
    @if (isset($comprobantes))
        @include('libro_mayor_cuenta_general.partials.table')
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

        function Modal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function procesar() {
            if(!validar()){
                return false;
            }
            search();
        }

        function validar(){
            if($("#fecha_i").val() == ""){
                Modal("[FECHA INICIAL REQUERIDA]");
                return false;
            }
            if($("#fecha_f").val() == ""){
                Modal("[FECHA FINAL REQUERIDA]");
                return false;
            }
            if($("#plan_cuenta_id >option:selected").val() == ""){
                Modal("[PLAN DE CUENTA NO SELECCIONADO.]");
                return false;
            }
            return true;
        }

        function search(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('libro.mayor.cuenta.general.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('libro.mayor.cuenta.general.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function excel(){
            var id = $("#empresa_id").val();
            var url = "{{ route('libro.mayor.cuenta.general.excel',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function pdf(){
            var id = $("#empresa_id").val();
            var url = "{{ route('libro.mayor.cuenta.general.pdf',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function cambiarf(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('libro.mayor.cuenta.general.f.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
