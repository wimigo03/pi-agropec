<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('comprobantes.partials.search')
    @include('comprobantes.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('#empresa_id').select2({
                theme: "bootstrap4",
                placeholder: "--Empresa--",
                width: '100%'
            });

            $('#tipo').select2({
                theme: "bootstrap4",
                placeholder: "--Tipo--",
                width: '100%'
            });

            $('#copia').select2({
                theme: "bootstrap4",
                placeholder: "--Copia--",
                width: '100%'
            });

            $('#estado').select2({
                theme: "bootstrap4",
                placeholder: "--Estado--",
                width: '100%'
            });

            $('#user_id').select2({
                theme: "bootstrap4",
                placeholder: "--Creado por--",
                width: '100%'
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

            $('#user_id').on('select2:open', function(e) {
                if($("#empresa_id >option:selected").val() == ""){
                    Modal("Para continuar se debe seleccionar una <b>[EMPRESA]</b>.");
                }
            });

            if($("#empresa_id >option:selected").val() != ''){
                var id = $("#empresa_id >option:selected").val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                getUsuarios(id,CSRF_TOKEN);
            }

            $('#dataTable').DataTable({
                bFilter: true,
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('comprobante.index') }}",
                columns: [
                    {
                        data: 'empresa',
                        name: 'b.alias',
                        class: 'text-center p-1 font-roboto-11'
                    },
                    {
                        data: 'tipos',
                        name: 'tipos',
                        class: 'text-center p-1 font-roboto-11'
                    },
                    {
                        data: 'fecha',
                        name: 'fecha',
                        class: 'text-center p-1 font-roboto-11',
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                var dateParts = data.split("-");
                                var year = dateParts[0].slice(-2);
                                var month = dateParts[1];
                                var day = dateParts[2];
                                return day + '-' + month + '-' + year;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'nro_comprobante',
                        name: 'a.nro_comprobante',
                        class: 'text-center p-1 font-roboto-11'
                    },
                    {
                        data: 'creado',
                        name: 'creado',
                        class: 'text-center p-1 font-roboto-11',
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                var dateParts = data.split("-");
                                var year = dateParts[0].slice(-2);
                                var month = dateParts[1];
                                var day = dateParts[2];
                                return day + '-' + month + '-' + year;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'concepto',
                        name: 'a.concepto',
                        class: 'text-justify p-1 font-roboto-11'
                    },
                    {
                        data: 'monto',
                        name: 'a.monto',
                        class: 'text-justify p-1 font-roboto-11'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        class: 'text-center p-1 font-roboto-11',
                        render: function(data, type, row){
                            if(row.status === 'PENDIENTE'){
                                return '<span class="badge-with-padding badge badge-secondary">PENDIENTE</span>';
                            }else if(row.status == 'APROBADO'){
                                return '<span class="badge-with-padding badge badge-success">APROBADO</span>';
                            }else if(row.status == 'ANULADO'){
                                return '<span class="badge-with-padding badge badge-danger">ANULADO</span>';
                            }else if(row.status == 'ELIMINADO'){
                                return '<span class="badge-with-padding badge badge-danger">ELIMINADO</span>';
                            }else{
                                return '#';
                            }
                        }
                    },
                    {
                        data: 'username',
                        name: 'c.username',
                        class: 'text-center p-1 font-roboto-11'
                    },
                    {
                        data: 'copia',
                        name: 'copia',
                        class: 'text-center p-1 font-roboto-11',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'bars',
                        name: 'bars',
                        class: 'text-center p-1 font-roboto-11',
                        orderable: false,
                        searchable: false
                    },
                ],
                initComplete: function () {
                    var api = this.api();
                    var columnCount = api.columns().nodes().length;

                    api.columns().every(function (index) {
                        if (index >= columnCount - 2) {
                            return;
                        }
                        var column = this;
                        var input = document.createElement("input");
                        input.style.width = "100%";
                        $(input).addClass('form-control font-roboto-12').appendTo($(column.footer()).empty()).on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? val : '', true, false).draw();
                        });
                    });
                },
                order: [[2, 'desc']],
                language: datatableLanguageConfig
            });
        });

        $('#empresa_id').change(function() {
            var id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            getUsuarios(id,CSRF_TOKEN);
        });

        function getUsuarios(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/comprobantes/get_usuarios',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){
                    if(data.usuarios){
                        var arr = Object.values($.parseJSON(data.usuarios));
                        $("#user_id").empty();
                        var select = $("#user_id");
                        select.append($("<option></option>").attr("value", '').text('--Seleccionar--'));
                        $.each(arr, function(index, json) {
                            var opcion = $("<option></option>").attr("value", json.id).text(json.name);
                            select.append(opcion);
                        });
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
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

        function countCharsI(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha_i").value;
                if(!validarFecha(date)){
                    document.getElementById('fecha_i').value = '';
                }
            }
        }

        function countCharsF(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha_f").value;
                if(!validarFecha(date)){
                    document.getElementById('fecha_f').value = '';
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

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                search();
                event.preventDefault();
            }
        });

        function Modal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function comprobantesf(){
            var url = "{{ route('comprobantef.index') }}";
            window.location.href = url;
        }

        function create(){
            var url = "{{ route('comprobante.create') }}";
            window.location.href = url;
        }

        function search(){
            var url = "{{ route('comprobante.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function excel() {
            var url = "{{ route('comprobante.excel') }}";
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
                    a.download = 'comprobantes.xlsx';
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

        function limpiar(){
            var url = "{{ route('comprobante.index') }}";
            window.location.href = url;
        }
    </script>
@stop

