<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('empresas.partials.form-editar')
    <div class="form-group row">
        <div class="col-md-12 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                <i class="fas fa-paper-plane"></i>&nbsp;Actualizar
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

        var Modal = function(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function agregar(){
            if(!validarModulos()){
                return false;
            }
            if(!validarRepetidos()){
                return false;
            }
            cargar();
        }

        function validarHeader(){
            if($("#nombre_comercial").val() == ""){
                Modal("El [NOMBRE COMERCIAL] es un dato obligatorio.");
                return false;
            }
            if($("#direccion").val() == ""){
                Modal("La [DIRECCION] es un dato obligatorio.");
                return false;
            }
            if($("#alias").val() == ""){
                Modal("el [ALIAS] es un dato obligatorio.");
                return false;
            }
            return true;
        }

        function validarModulos(){
            if($("#modulo_id >option:selected").val() == ""){
                Modal("Se debe seleccionar un [MODULO] para continuar.");
                return false;
            }
            return true;
        }

        function validarRepetidos(){
            var modulos = $("#detalle_tabla tbody tr");
            if(modulos.length > 0){
                var modulo = $("#modulo_id >option:selected").val();
                for(var i = 0;i < modulos.length;i++){
                    var tr = modulos[i];
                    var modulo_id = $(tr).find(".modulo_id").val();
                    if(modulo == modulo_id){
                        Modal("El modulo seleccionado ya se encuentra en la lista.");
                        return false;
                    }
                }
            }
            return true;
        }

        function cargar(){
            var modulo_id = $("#modulo_id >option:selected").val();
            var modulo = $("#modulo_id option:selected").text();
            var fila = "<tr class='font-roboto-11'>"+
                            "<td class='text-justify p-1'>"+
                                "<input type='hidden' class='modulo_id' name='modulo_id[]' value='" + modulo_id + "'>" + modulo +
                            "</td>"+
                            "<td class='text-center p-1'>"+
                                "HABILITADO" +
                            "</td>"+
                            "<td class='text-center p-1'>"+
                                "<span class='badge-with-padding badge badge-danger' onclick='eliminarItem(this);'>" +
                                      "<i class='fas fa-trash fa-fw'></i>" +
                                 "</span>" +
                            "</td>"
                        "</tr>";

            $("#detalle_tabla").append(fila);
            $('#modulo_id').val('').trigger('change');
            $("#btn-registro").show();
        }

        function eliminarItem(thiss){
            var tr = $(thiss).parents("tr:eq(0)");
            tr.remove();
        }

        function procesar() {
            if(!validarHeader()){
                return false;
            }
            if(!contarRegistros()){
                return false;
            }
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function contarRegistros(){
            var modulos = $("#detalle_tabla tbody tr");
            if(modulos.length === 0){
                Modal("Al menos debe tener un modulo configurado.");
                return false;
            }
            return true;
        }

        function confirmar() {
            var url = "{{ route('empresas.update') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            var id = $("#pi_cliente_id").val();
            var url = "{{ route('empresas.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function valideNumberInteger(evt){
            var code = (evt.which) ? evt.which : evt.keyCode;
            if(code>=48 && code<=57){
                return true;
            }else{
                return false;
            }
        }
    </script>
@stop
