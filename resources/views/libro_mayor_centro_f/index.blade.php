<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('libro_mayor_centro_f.partials.search')
    @include('libro_mayor_centro_f.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%',
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    }
                }
            });

            $('#estado').select2({
                theme: "bootstrap4",
                placeholder: "--Estado--",
                width: '100%',
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    }
                }
            });

            var cleave = new Cleave('#fecha_i', {
                date: true,
                datePattern: ['d', 'm', 'Y'],
                delimiter: '-'
            });

            var cleave = new Cleave('#fecha_f', {
                date: true,
                datePattern: ['d', 'm', 'Y'],
                delimiter: '-'
            });

            $("#fecha_i").datepicker({
                inline: false,
                dateFormat: "dd-mm-yy",
                autoClose: true,
            });

            $("#fecha_f").datepicker({
                inline: false,
                dateFormat: "dd-mm-yy",
                autoClose: true,
            });

            $('#centro_id').on('select2:open', function(e) {
                if($("#empresa_id >option:selected").val() == ""){
                    Modal("Para continuar se debe seleccionar una <b>[EMPRESA]</b>.");
                }
            });

            $('#sub_centro_id').on('select2:open', function(e) {
                if($("#empresa_id >option:selected").val() == ""){
                    Modal("Para continuar se debe seleccionar una <b>[EMPRESA]</b>.");
                }
            });

            $('#sub_centro_id').on('select2:open', function(e) {
                if($("#centro_id >option:selected").val() == ""){
                    Modal("Para continuar se debe seleccionar una <b>[CENTRO]</b>.");
                }
            });

            if($("#empresa_id >option:selected").val() != ''){
                var id = $("#empresa_id >option:selected").val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                getCentros(id,CSRF_TOKEN);
            }

            if($("#centro_id >option:selected").val() != ''){
                if (!$("#centro_id").length) {
                    var id = $("#centro_id >option:selected").val();
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    getSubCentros(id,CSRF_TOKEN);
                }
            }
        });

        $('#empresa_id').change(function() {
            var id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            getCentros(id,CSRF_TOKEN);
        });

        $('#centro_id').change(function() {
            var id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            getSubCentros(id,CSRF_TOKEN);
        });

        function getCentros(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/libro-mayor-centro-f/get_centros',
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

        function getSubCentros(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/libro-mayor-centro-f/get_subcentros',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){
                    if(data.subcentros){
                        var arr = Object.values($.parseJSON(data.subcentros));
                        $("#sub_centro_id").empty();
                        var select = $("#sub_centro_id");
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

        function Modal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function procesar() {
            if(!validar()){
                return false;
            }
            search();
        }

        function validar(){
            if($("#empresa_id >option:selected").val() == ""){
                Modal("[EMPRESA ES REQUERIDA]");
                return false;
            }
            if($("#fecha_i").val() == ""){
                Modal("[FECHA INICIAL REQUERIDA]");
                return false;
            }
            if($("#fecha_f").val() == ""){
                Modal("[FECHA FINAL REQUERIDA]");
                return false;
            }
            if($("#sub_centro_id >option:selected").val() == ""){
                Modal("[CENTRO NO SELECCIONADO.]");
                return false;
            }
            return true;
        }

        function search(){
            var url = "{{ route('libro.mayor.centro.f.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function limpiar(){
            var url = "{{ route('libro.mayor.centro.f.index') }}";
            window.location.href = url;
        }

        function excel() {
            var url = "{{ route('libro.mayor.centro.f.excel') }}";
            $(".btn").hide();
            $(".spinner-btn-send").show();
            var form = $("#form");
            var formData = form.serialize();
            $.ajax({
                url: url,
                type: 'GET',
                data: formData,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(response);
                    a.href = url;
                    a.download = 'libro_mayor_centro.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    $(".spinner-btn-send").hide();
                    $(".btn").show();
                },
                error: function(xhr, status, error) {
                    alert('Hubo un error al exportar el archivo: ' + xhr.responseText);
                    $(".spinner-btn-send").hide();
                    $(".btn").show();
                }
            });
        }

        function pdf() {
            var url = "{{ route('libro.mayor.centro.f.pdf') }}";
            $(".btn").hide();
            $(".spinner-btn-send").show();
            var form = $("#form");
            var formData = form.serialize();
            $.ajax({
                url: url,
                type: 'GET',
                data: formData,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(response);
                    a.href = url;
                    a.download = 'libro_mayor_centro.pdf';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    $(".spinner-btn-send").hide();
                    $(".btn").show();
                },
                error: function(xhr, status, error) {
                    alert('Hubo un error al exportar el archivo: ' + xhr.responseText);
                    $(".spinner-btn-send").hide();
                    $(".btn").show();
                }
            });
        }

        function cambiari(){
            var url = "{{ route('libro.mayor.centro.index') }}";
            window.location.href = url;
        }
    </script>
@stop
