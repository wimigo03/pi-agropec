<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('caja_venta.partials.form-create')
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

            var cleave = new Cleave('#monto', {
                numeral: true,
                numeralDecimalScale: 2,
                rawValueTrimPrefix: true
            });
        });

        function Modal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                procesar();
                event.preventDefault();
            }
        });

        function procesar() {
            if(!validar()){
                return false;
            }

            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function validar(){
            if($("#sucursal_id >option:selected").val() == ""){
                Modal("<center>[LA SUCURSAL ES REQUERIDA]</center>");
                return false;
            }
            if($("#user_id >option:selected").val() == ""){
                Modal("<center>[LA USUARIO ES REQUERIDO]</center>");
                return false;
            }
            if($("#monto:selected").val() == ""){
                Modal("<center>[EL MONTO ES REQUERIDO]</center>");
                return false;
            }

            return true;
        }

        function confirmar(){
            var url = "{{ route('caja.venta.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('caja.venta.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
