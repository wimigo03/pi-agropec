<!DOCTYPE html>
@extends('layouts.dashboard')
<style>
    .select2 + .select2-container .select2-selection__rendered {
        font-size: 11px;
    }
    .select2-results__option {
        font-size: 13px;
    }
    .obligatorio {
        border: 1px solid red !important;
    }
    .font-weight-bold {
        font-weight: bold;
    }
    .select2-container--obligatorio .select2-selection {
        border-color: red !important;
    }
</style>
@section('content')
    @include('tipo_cambios.partials.form-editar')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            var cleave = new Cleave('#fecha', {
                date: true,
                datePattern: ['d', 'm', 'Y'],
                delimiter: '-'
            });

            $("#fecha").datepicker({
                inline: false,
                dateFormat: "dd-mm-yy",
                autoClose: true,
            });

            obligatorio();
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

        function countChars(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha").value;
                if(!validarFecha(date)){
                    document.getElementById('fecha').value = '';
                }
            }
        }

        function validarFecha(date) {
            var regexFecha = /^\d{2}\/\d{2}\/\d{4}$/;
            if (regexFecha.test(date)) {
                var partesFecha = date.split('/');
                var dia = parseInt(partesFecha[0], 10);
                var mes = parseInt(partesFecha[1], 10);
                var anio = parseInt(partesFecha[2], 10);
                if (dia >= 1 && dia <= 31 && mes >= 1 && mes <= 12 && anio >= 1900) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function obligatorio(){
            if($("#fecha").val() !== ""){
                $("#fecha").removeClass('obligatorio');
            }else{
                $("#fecha").addClass('obligatorio');
            }

            if($("#ufv").val() !== ""){
                $("#ufv").removeClass('obligatorio');
            }else{
                $("#ufv").addClass('obligatorio');
            }

            if($("#oficial").val() !== ""){
                $("#oficial").removeClass('obligatorio');
            }else{
                $("#oficial").addClass('obligatorio');
            }

            if($("#compra").val() !== ""){
                $("#compra").removeClass('obligatorio');
            }else{
                $("#compra").addClass('obligatorio');
            }

            if($("#venta").val() !== ""){
                $("#venta").removeClass('obligatorio');
            }else{
                $("#venta").addClass('obligatorio');
            }
        }

        function valideNumberConDecimal(evt) {
            var code = (evt.which) ? evt.which : evt.keyCode;
            if ((code >= 48 && code <= 57) || code === 46 || code === 8) {
                if (code === 46 && evt.target.value.indexOf('.') !== -1) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        }

        function procesar() {
            if(!validar()){
                return false;
            }
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function validar(){
            if($("#date").val() == ""){
                alerta("<center>La <b>[FECHA]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#ufv").val() == ""){
                alerta("<center>El <b>[UFV]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#oficial").val() == ""){
                alerta("<center>El <b>[OFICIAL]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#compra").val() == ""){
                alerta("<center>El <b>[COMPRA]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#venta").val() == ""){
                alerta("<center>El <b>[VENTA]</b> es un dato obligatorio...</center>");
                return false;
            }
            return true;
        }

        function confirmar(){
            var url = "{{ route('tipo.cambio.update') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function cancelar(){
            var url = "{{ route('tipo.cambio.index') }}";
            window.location.href = url;
        }
    </script>
@stop
