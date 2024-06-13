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
    @include('mesas.partials.form-create')
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

            if($("#sucursal_id >option:selected").val() != ''){
                var id = $("#sucursal_id >option:selected").val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                getZonas(id,CSRF_TOKEN);
            }

            obligatorio();
        });

        $('#sucursal_id').change(function() {
            var id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            getZonas(id,CSRF_TOKEN);
        });

        function getZonas(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/zonas/get_datos_by_sucursal',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){
                    if(data.zonas){
                        var arr = Object.values($.parseJSON(data.zonas));
                        $("#zona_id").empty();
                        var select = $("#zona_id");
                        select.append($("<option></option>").attr("value", '').text('--Zona--'));
                        var zonaIdSeleccionado = localStorage.getItem('zonaIdSeleccionado');
                        $.each(arr, function(index, json) {
                            var opcion = $("<option></option>").attr("value", json.id).text(json.nombre);
                            if (json.id == zonaIdSeleccionado) {
                                opcion.attr('selected', 'selected');
                            }
                            select.append(opcion);
                        });
                        select.on('change', function() {
                            localStorage.setItem('zonaIdSeleccionado', $(this).val());
                        });
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        function alerta(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function obligatorio(){
            if($("#sucursal_id >option:selected").val() !== ""){
                $("#obligatorio_sucursal_id").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_sucursal_id").addClass('select2-container--obligatorio');
            }
            if($("#zona_id >option:selected").val() !== ""){
                $("#obligatorio_zona_id").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_zona_id").addClass('select2-container--obligatorio');
            }
            if($("#numero").val() !== ""){
                $("#numero").removeClass('obligatorio');
            }else{
                $("#numero").addClass('obligatorio');
            }

            if($("#sillas").val() !== ""){
                $("#sillas").removeClass('obligatorio');
            }else{
                $("#sillas").addClass('obligatorio');
            }
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
            if($("#nombre").val() == ""){
                alerta("<center>El campo <b>[Zona]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#codigo").val() == ""){
                alerta("<center>El campo <b>[Celular]</b> es un dato obligatorio...</center>");
                return false;
            }
            return true;
        }

        function confirmar(){
            var url = "{{ route('mesas.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('mesas.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
