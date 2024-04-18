<!DOCTYPE html>
<title>Pi-Resto | Mesas</title>
@extends('layouts.dashboard')
@section('content')
    @include('mesas.partials.search')
    <div class="form-group row">
        <div class="col-md-6 px-0 pr-1">
            @can('mesas.create')
                <span class="tts:right tts-slideIn tts-custom" aria-label="Crear" style="cursor: pointer;">
                    <button class="btn btn-outline-success font-verdana" type="button" onclick="create();">
                        <i class="fas fa-plus fa-fw"></i>
                    </button>
                </span>
            @endcan
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
        <div class="col-md-6 px-0 pl-1 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="search();">
                <i class="fas fa-search fa-fw"></i>&nbsp;Buscar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
                <i class="fas fa-eraser fa-fw"></i>&nbsp;Limpiar
            </button>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
    @include('mesas.partials.table')
    {{--@include('mesas.partials.tableAjax')--}}
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            /*$('#dataTable').DataTable({
                "processing":true,
                "serverSide":true,
                "ajax": {
                    "url": "{{ route('mesas.indexAjax') }}",
                    "type": "GET",
                    "data": function (datos) {
                        datos.empresa_id = $("#empresa_id").val();
                    },
                },
                "columns": [
                    {"data": 'id', "name":'id', "class":'text-left p-1 font-roboto', "searchable": true, "orderable": true,},
                    {"data": "nombre_sucursal", "name": "sucursal.nombre", "class": "text-left p-1 font-roboto", "searchable": true, "orderable": true,},
                    {"data": 'nombre_zona', "name":'zona.nombre', "class":'text-left p-1 font-roboto', "searchable": true, "orderable": true,},
                    {"data": 'numero', "name":'numero', "class":'text-left p-1 font-roboto', "searchable": true, "orderable": true,},
                    {"data": 'sillas', "name":'sillas', "class":'text-left p-1 font-roboto', "searchable": true, "orderable": true,},
                    {"data": 'detalle', "name":'detalle', "class":'text-left p-1 font-roboto', "searchable": true, "orderable": true,},
                    {"data": 'estado_mesa', "name":'estado_mesa', "class":'text-center p-1 font-roboto', "searchable": true, "orderable": true,
                        render: function(data, type, row)
                        {
                            if(row.estado_mesa === 'HABILITADO'){
                                return '<span class="badge-with-padding badge badge-success">HABILITADO</span>';
                            }else{
                                return '<span class="badge-with-padding badge badge-danger">DESHABILITADO</span>';
                            }
                        }
                    },
                    {
                        "targets": -1,
                        "data": null,
                        "defaultContent": '<span class="badge-with-padding badge badge-warning text-white">' + 
                                                '<i class="fas fa-edit fa-fw"></i>' +
                                            '</span>' + 
                                            '<span class="badge-with-padding badge badge-danger">' + 
                                                '<i class="fas fa-lg fa-arrow-alt-circle-down"></i>' + 
                                            '</span>'
                    }
                ],
                "columnDefs": [
                    {
                        "targets": -1,
                        "visible": true,
                    }
                ],
                    "createdRow": function (row, data, dataIndex) {
                    $(row).addClass('table-row text-center p-1');
                },
                "iDisplayLength": 10,
                "ordering": true,
                "order": [[ 0, "desc" ]],
                "language":{
                    "url": '{{ asset("json/datatable-es.json") }}'
                }
            });*/

            $('#estado').select2({
                theme: "bootstrap4",
                placeholder: "--Estado--",
                width: '100%'
            });

            $('#sucursal_id').select2({
                theme: "bootstrap4",
                placeholder: "--Sucursal--",
                width: '100%'
            });

            $('#zona_id').select2({
                theme: "bootstrap4",
                placeholder: "--Zona--",
                width: '100%'
            });

            if($("#sucursal_id >option:selected").val() != ''){
                var id = $("#sucursal_id >option:selected").val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                getZonas(id,CSRF_TOKEN);
            }
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

        function valideNumberSinDecimal(evt) {
            var code = (evt.which) ? evt.which : evt.keyCode;
            if ((code >= 48 && code <= 57) || code === 8) {
                return true;
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

        function create(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var url = "{{ route('mesas.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function search(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('mesas.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('mesas.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop