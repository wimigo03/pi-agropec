<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('balance_apertura.partials.form-create')
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
        });

        function alerta(mensaje){
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
            if($("#anho").val() == ""){
                alerta("[LA GESTION ES UN DATO REQUERIDO0]");
                return false;
            }
            return true;
        }

        function confirmar(){
            var url = "{{ route('balance.apertura.store') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function cancelar(){
            var url = "{{ route('balance.apertura.index') }}";
            window.location.href = url;
        }
    </script>
@stop
