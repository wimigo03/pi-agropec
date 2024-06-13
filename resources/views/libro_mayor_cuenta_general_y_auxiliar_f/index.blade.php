<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('libro_mayor_cuenta_general_y_auxiliar_f.partials.search')
    @include('libro_mayor_cuenta_general_y_auxiliar_f.partials.table')
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

            $('#plan_cuenta_id').on('select2:open', function(e) {
                if($("#empresa_id >option:selected").val() == ""){
                    Modal("Para continuar se debe seleccionar una <b>[EMPRESA]</b>.");
                }
            });

            $('#plan_cuenta_auxiliar_id').on('select2:open', function(e) {
                if($("#empresa_id >option:selected").val() == ""){
                    Modal("Para continuar se debe seleccionar una <b>[EMPRESA]</b>.");
                }
            });

            if($("#empresa_id >option:selected").val() != ''){
                var id = $("#empresa_id >option:selected").val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                getPlanCuentas(id,CSRF_TOKEN);
                getPlanCuentasAuxiliares(id,CSRF_TOKEN);
            }
        });

        $('#empresa_id').change(function() {
            var id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            getPlanCuentas(id,CSRF_TOKEN);
            getPlanCuentasAuxiliares(id,CSRF_TOKEN);
        });

        function getPlanCuentas(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/libro-mayor-cuenta-y-auxiliar-f/get_plancuentas',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){
                    if(data.plan_cuentas){
                        var arr = Object.values($.parseJSON(data.plan_cuentas));
                        $("#plan_cuenta_id").empty();
                        var select = $("#plan_cuenta_id");
                        select.append($("<option></option>").attr("value", '').text('--Seleccionar--'));
                        $.each(arr, function(index, json) {
                            var opcion = $("<option></option>").attr("value", json.id).text(json.cuenta_contable);
                            select.append(opcion);
                        });
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        function getPlanCuentasAuxiliares(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/libro-mayor-cuenta-y-auxiliar-f/get_plancuentasauxiliares',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){
                    if(data.plan_cuentas_auxiliares){
                        var arr = Object.values($.parseJSON(data.plan_cuentas_auxiliares));
                        $("#plan_cuenta_auxiliar_id").empty();
                        var select = $("#plan_cuenta_auxiliar_id");
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
            if($("#plan_cuenta_id >option:selected").val() == ""){
                Modal("[PLAN DE CUENTA NO SELECCIONADO.]");
                return false;
            }
            if($("#plan_cuenta_auxiliar_id >option:selected").val() == ""){
                Modal("[PLAN DE CUENTA AUXILIAR NO SELECCIONADO.]");
                return false;
            }
            return true;
        }

        function search(){
            var url = "{{ route('libro.mayor.cuenta.y.auxiliar.f.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function limpiar(){
            var url = "{{ route('libro.mayor.cuenta.y.auxiliar.f.index') }}";
            window.location.href = url;
        }

        function excel() {
            var url = "{{ route('libro.mayor.cuenta.y.auxiliar.f.excel') }}";
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
                    a.download = 'libro_mayor_cuenta_y_auxiliar.xlsx';
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
            var url = "{{ route('libro.mayor.cuenta.y.auxiliar.f.pdf') }}";
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
                    a.download = 'libro_mayor_cuenta_y_auxiliar.pdf';
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
            var url = "{{ route('libro.mayor.cuenta.y.auxiliar.index') }}";
            window.location.href = url;
        }
    </script>
@stop
