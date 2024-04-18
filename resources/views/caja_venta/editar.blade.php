<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('asientos_automaticos.partials.form-editar')
    <div class="form-group row">
        <div class="col-md-12 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                <i class="fas fa-paper-plane"></i>&nbsp;Procesar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
                &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
            </button>
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
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

        function copiar_concepto(){
            var concepto = $("#concepto").val();
            document.getElementById('glosa').value = concepto;
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
            if($("#nombre").val() == ""){
                Modal("<center>[EL NOMBRE DEL ASIENTO AUTOMATICO ES REQUERIDO.]</center>");
                return false;
            }
            if($("#moneda_id >option:selected").val() == ""){
                Modal("<center>[LA MONEDA ES REQUERIDA]</center>");
                return false;
            }
            if($("#modulo_id >option:selected").val() == ""){
                Modal("<center>[EL MODULO ES REQUERIDA]</center>");
                return false;
            }
            if($("#tipo >option:selected").val() == ""){
                Modal("<center>[EL TIPO DE ASIENTO ES REQUERIDO]</center>");
                return false;
            }
            if($("#concepto").val() == ""){
                Modal("<center>[EL CONCEPTO ES REQUERIDO.]</center>");
                return false;
            }
            if($("#plan_cuenta_id >option:selected").val() == ""){
                Modal("<center>[EL PLAN DE CUENTA ES REQUERIDO]</center>");
                return false;
            }
            if($("#glosa").val() == ""){
                Modal("<center>[LA GLOSA ES REQUERIDO.]</center>");
                return false;
            }

            return true;
        }

        function confirmar(){
            var url = "{{ route('asiento.automatico.update') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('asiento.automatico.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
