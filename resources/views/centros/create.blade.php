<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('centros.partials.form-create')
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

        var Modal = function(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function procesar() {
            if(!validar()){
                return false;
            }
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function validar() {
            if($("#empresa_id >option:selected").val() == ""){
                Modal("El campo de seleccion <b>[Empresa]</b> es un dato obligatorio...");
                return false;
            }
            if ($("#nombre").val() == "") {
                Modal('El campo <b>[Nombre]</b> es un dato obligatorio.');
                return false;
            }
            if ($("#abreviatura").val() == "") {
                Modal('El campo <b>[Abreviatura]</b> es un dato obligaorio.');
                return false;
            }
            return true;
        }

        function confirmar() {
            var url = "{{ route('centros.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            var url = "{{ route('centros.index') }}";
            window.location.href = url;
        }
    </script>
@stop
