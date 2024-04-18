<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('asientos_automaticos.partials.form-create')
    <div class="form-group row" id="btn-registro">
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
            $("#btn-registro").hide();
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

        function agregar(){
            if(!validarHeaderDetalle()){
                return false;
            }
            if(!validarRepetidos()){
                return false;
            }
            cargarDetalle();
        }

        function validarHeaderDetalle(){
            if($("#plan_cuenta_id >option:selected").val() == ""){
                Modal("<center>[EL PLAN DE CUENTA ES REQUERIDO PARA EL DETALLE]</center>");
                return false;
            }
            if($("#tipo >option:selected").val() == ""){
                Modal("<center>[EL TIPO ES REQUERIDO PARA EL DETALLE]</center>");
                return false;
            }
            if($("#glosa").val() == ""){
                Modal("<center>[LA GLOSA ES REQUERIDA PARA EL DETALLE]</center>");
                return false;
            }
            return true;
        }

        function validarRepetidos(){
            var planes_cuentas = $("#detalle_tabla tbody tr");
            if(planes_cuentas.length>0){
                var plan_cuenta = $("#plan_cuenta_id >option:selected").val();
                for(var i=0;i<planes_cuentas.length;i++){
                    var tr = planes_cuentas[i];
                    var plan_cuenta_id = $(tr).find(".plan_cuenta_id").val();
                    if(plan_cuenta == plan_cuenta_id){
                        Modal("<center>[EL PLAN DE CUENTA SELECCIONADO YA EXISTE EN EL REGISTRO.]</center>");
                        return false;
                    }
                }
            }
            return true;
        }

        function cargarDetalle(){
            var plan_cuenta_id = $("#plan_cuenta_id >option:selected").val();
            var plan_cuenta_texto = $("#plan_cuenta_id option:selected").text();
            var quitar = /[()]/g;
            var string_texto = plan_cuenta_texto.replace(quitar, '');
            string_texto = string_texto.split('_');
            var plan_cuenta = string_texto[0];
            var tipo_id = $("#tipo >option:selected").val();
            var tipo = $("#tipo option:selected").text();
            var glosa = $("#glosa").val();
            var fila = "<tr class='font-roboto-11'>"+
                            "<td class='text-justify p-1'>"+
                                "<input type='hidden' class='plan_cuenta_id' name='plan_cuenta_id[]' value='" + plan_cuenta_id + "'>" + plan_cuenta +
                            "</td>"+
                            "<td class='text-center p-1'>"+
                                "<input type='hidden' name='tipo[]' value='" + tipo_id + "'>" + tipo +
                            "</td>"+
                            "<td class='text-justify p-1'>"+
                                "<input type='hidden' name='glosa[]' value='" + glosa + "'>" + glosa +
                            "</td>"+
                            "<td class='text-center p-1'>"+
                                "<span class='badge-with-padding badge badge-danger' onclick='eliminarItem(this);'>" +
                                      "<i class='fas fa-trash fa-fw'></i>" +
                                 "</span>"
                            "</td>"
                        "</tr>";

            $("#detalle_tabla").append(fila);
            $('#plan_cuenta_id').val('').trigger('change');
            $('#tipo').val('').trigger('change');
            document.getElementById('glosa').value = '';
            $("#btn-registro").show();
        }

        function eliminarItem(thiss){
            var tr = $(thiss).parents("tr:eq(0)");
            tr.remove();
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
            if($("#modulo_id >option:selected").val() == ""){
                Modal("<center>[EL MODULO ES REQUERIDA]</center>");
                return false;
            }

            return true;
        }

        function confirmar(){
            var url = "{{ route('asiento.automatico.store') }}";
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
