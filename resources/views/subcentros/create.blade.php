<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('subcentros.partials.form-create')
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

            if($("#empresa_id >option:selected").val() != ''){
                var id = $("#empresa_id >option:selected").val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                getCentros(id,CSRF_TOKEN);
            }
        });

        $('#empresa_id').change(function() {
            var id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            getCentros(id,CSRF_TOKEN);
        });

        function getCentros(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/sub-centros/get_centros',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){
                    if(data.centros){
                        var arr = Object.values($.parseJSON(data.centros));
                        $("#centro_id").empty();
                        var select = $("#centro_id");
                        select.append($("<option></option>").attr("value", '').text('--Seleccionar--'));
                        $.each(arr, function(index, json) {
                            var opcion = $("<option></option>").attr("value", json.id).text(json.nombre);
                            select.append(opcion);
                        });
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

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
            if($("#centro_id >option:selected").val() == ""){
                Modal("El campo de seleccion <b>[Centro Contable]</b> es un dato obligatorio...");
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
            var url = "{{ route('sub.centros.store') }}";
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
