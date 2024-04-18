<!DOCTYPE html>
@extends('layouts.dashboard')
<style>
    #treeview {
        min-height: 200px;            
    }
    #contenido {
        min-height: 200px;
    }
    #treeview li a {
        font-size: 12px;
        font-family: "Roboto", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }
    #empresa_id + .select2-container .select2-selection__rendered {
        font-size: 12px;
    }
</style>
@section('content')
    @if (isset($cargos))
        <div class="form-group row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-body">
                            <div id="treeview"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-body">
                            <div id="contenido">
                                <input type="hidden" value="{{ $cargos[0]->id }}" name="cargo_id" id="cargo_id">    
                                @include('cargos.partials.contenido')
                                <div class="form-group row">
                                    <div class="col-md-12 text-right">
                                        @can('cargos.habilitar')
                                            <button class="btn btn-outline-danger font-verdana" type="button" onclick="deshabilitar();" id="btn_deshabilitar">
                                                <i class="fas fa-times"></i>&nbsp;Deshabilitar
                                            </button>
                                        @endcan
                                        @can('cargos.habilitar')
                                        <button class="btn btn-outline-success font-verdana" type="button" onclick="habilitar();" id="btn_habilitar">
                                            <i class="fas fa-check"></i>&nbsp;Habilitar
                                        </button>
                                        @endcan
                                        @can('cargos.create')
                                            <button class="btn btn-outline-success font-verdana" type="button" onclick="crear();" id="btn_create_dependiente">
                                                <i class="fas fa-plus"></i>&nbsp;Crear dependiente
                                            </button>
                                        @endcan
                                        @can('cargos.modificar')
                                            <button class="btn btn-outline-warning font-verdana" type="button" onclick="modificar();" id="btn_modificar_dependiente">
                                                &nbsp;<i class="fas fa-edit"></i>&nbsp;Modificar
                                            </button>
                                        @endcan
                                        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $("#btn_deshabilitar").hide();
            $("#btn_habilitar").hide();

            if(document.getElementById('cargo_id')){
                datosCargo(document.getElementById('cargo_id').value);
            }
        });

        $(function () {
            var nodo_id = {{ request('nodeId', 0) }};

            $('#treeview').jstree({
                'core': {
                    'data': {!! json_encode($tree) !!},
                    'themes' : {
                        'variant' : 'large',
                        'animation' : 0
                    }
                }
            });

            $('#treeview').on('activate_node.jstree', function (e, data) {
                if (data.node && data.node.children.length > 0) {
                    $('#treeview').jstree('toggle_node', data.node);
                }
                datosCargo(data.node.id);
            });

            $('#treeview').on('ready.jstree', function () {
                $('#treeview').jstree('select_node', nodo_id);
                if(nodo_id != ''){
                    datosCargo(nodo_id);
                }
            });
        });


        function datosCargo(id){
            $.ajax({
                type: 'GET',
                url: '/cargos/get_datos_cargo/'+id,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(json){
                    $('#cargo_id').val(json.id);
                    $('#nombre').text(json.nombre);
                    $('#codigo').text(json.codigo);
                    $('#email').text(json.email);
                    $('#descripcion').text(json.descripcion);
                    $('#alias').text(json.alias);
                    if(json.estado === '1'){
                        $('#estado').text('HABILITADO');
                        $("#btn_deshabilitar").show();
                        $("#btn_habilitar").hide();
                    }else{
                        $('#estado').text('NO HABILITADO');
                        $("#btn_deshabilitar").hide();
                        $("#btn_habilitar").show();
                        $("#btn_create_dependiente").hide();
                        $("#btn_modificar_dependiente").hide();
                    }
                    if(json.tipo === '1'){
                        $('#tipo').text('POR SERVICIO');
                    }else{
                        $('#tipo').text('PLANILLA DE SUELDO');
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        function crear(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#cargo_id").val();
            var url = "{{ route('cargos.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function modificar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#cargo_id").val();
            var url = "{{ route('cargos.editar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function habilitar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#cargo_id").val();
            var url = "{{ route('cargos.habilitar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function deshabilitar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#cargo_id").val();
            var url = "{{ route('cargos.deshabilitar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop