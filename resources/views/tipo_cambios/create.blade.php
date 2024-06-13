<!DOCTYPE html>
@extends('layouts.dashboard')
<style>
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
    @include('tipo_cambios.partials.form-create')
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
                maxDate: 0,
                autoClose: true
            });

            var cleave = new Cleave('#ufv', {
                numeral: true,
                numeralDecimalScale: 4,
                numeralThousandsGroupStyle: 'thousand'
            });

            var cleave = new Cleave('#oficial', {
                numeral: true,
                numeralDecimalScale: 2,
                numeralThousandsGroupStyle: 'thousand'
            });

            var cleave = new Cleave('#compra', {
                numeral: true,
                numeralDecimalScale: 2,
                numeralThousandsGroupStyle: 'thousand'
            });

            var cleave = new Cleave('#venta', {
                numeral: true,
                numeralDecimalScale: 2,
                numeralThousandsGroupStyle: 'thousand'
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
            $(".btn").hide();
            $(".spinner-btn").show();
            var url = "{{ route('tipo.cambio.store') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function cancelar(){
            var url = "{{ route('tipo.cambio.index') }}";
            window.location.href = url;
        }
    </script>
@stop
