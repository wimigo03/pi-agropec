<!DOCTYPE html>
@extends('layouts.dashboard')
<style>
    .jstree li > a > .jstree-icon {
        display:none !important;
    }

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

    #treeview_insumos {
        min-height: 200px;
    }
    #contenido_insumos {
        min-height: 200px;
    }
    #treeview_insumos li a {
        font-size: 12px;
        font-family: "Roboto", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }
    #empresa_id_insumos + .select2-container .select2-selection__rendered {
        font-size: 12px;
    }
</style>
@section('content')
@include('categorias.partials.menu')
@if (isset($categorias_platos) || isset($categorias_insumos))
    <div class="form-group row">
        <div class="col-md-12 px-1">
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-roboto-15" id="tab1" data-toggle="tab" href="#content1" role="tab" aria-controls="content1" aria-selected="true">
                        Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-roboto-15" id="tab2" data-toggle="tab" href="#content2" role="tab" aria-controls="content2" aria-selected="false">
                        Insumos
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabsContent">
                <div class="tab-pane fade show active" id="content1" role="tabpanel" aria-labelledby="tab1">
                    @if (isset($categorias_platos))
                        @include('categorias.partials.platos_final')
                    @endif
                </div>
                <div class="tab-pane fade" id="content2" role="tabpanel" aria-labelledby="tab2">
                    @if (isset($categorias_insumos))
                        @include('categorias.partials.insumos')
                    @endif
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
            $("#btn_deshabilitar_platos").hide();
            $("#btn_habilitar_platos").hide();
            $("#btn_sub_platos_master").hide();
            $("#btn_modificar_platos").hide();
            $("#btn_deshabilitar_insumos").hide();
            $("#btn_habilitar_insumos").hide();
            $("#btn_sub_insumos_master").hide();
            $("#btn_modificar_insumos").hide();

            var categoria_id = $('#categoria_id').val();
            if ($('#categoria_id option[value="' + categoria_id + '"]').length !== 0) {
                if($('#categoria_id').val() !== '#'){
                    datosCategoria(document.getElementById('categoria_id').value);
                }
            }

            var categoria_insumo_id = $('#categoria_insumo_id').val();
            if ($('#categoria_insumo_id option[value="' + categoria_insumo_id + '"]').length !== 0) {
                if($('#categoria_insumo_id').val() !== '#'){
                    datosCategoriaInsumos(document.getElementById('categoria_insumo_id').value);
                }
            }
        });

        $('#empresa_id').change(function() {
            var id = $(this).val();
            cargosByEmpresa(id);
        });

        function cargosByEmpresa(id){
            $(".btn").hide();
            $(".empresa-id-select-container").hide();
            $(".spinner-btn").show();
            var status_platos = '[]';
            var status_insumos = '[]';
            var url = "{{ route('categorias.index',[':id',':status_platos',':status_insumos']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status_platos',status_platos);
            url = url.replace(':status_insumos',status_insumos);
            window.location.href = url;
        }

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
                datosCategoria(data.node.id);
            });

            $('#treeview').on('ready.jstree', function () {
                $('#treeview').jstree('select_node', nodo_id);
                if(nodo_id != ''){
                    datosCategoria(nodo_id);
                }
            });

            /*Insumos*/
            var nodo_insumo_id = {{ request('nodeIdInsumo', 0) }};

            $('#treeview_insumos').jstree({
                'core': {
                    'data': {!! json_encode($tree_insumos) !!},
                    'themes' : {
                        'variant' : 'large',
                        'animation' : 0
                    }
                }
            });

            $('#treeview_insumos').on('activate_node.jstree', function (e, data) {
                if (data.node && data.node.children.length > 0) {
                    $('#treeview_insumos').jstree('toggle_node', data.node);
                }
                datosCategoriaInsumos(data.node.id);
            });

            $('#treeview_insumos').on('ready.jstree', function () {
                $('#treeview_insumos').jstree('select_node', nodo_insumo_id);
                if(nodo_insumo_id != ''){
                    datosCategoriaInsumos(nodo_insumo_id);
                }
            });
        });

        function datosCategoria(id){
            $.ajax({
                type: 'GET',
                url: '/categorias/get_datos_categoria/'+id,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(json){
                    $('#categoria_id').val(json.id);
                    $('#nombre').text(json.nombre);
                    $('#codigo').text(json.codigo);
                    $('#numeracion').text(json.numeracion);
                    $('#nivel').text(json.nivel);
                    if(json.nivel === '1'){
                        $("#btn_platos_master").show();
                        $("#btn_sub_platos_master").hide();
                        $("#btn_modificar_platos").show();
                        if(json.estado === '1'){
                            $("#btn_deshabilitar_platos").show();
                            $("#btn_habilitar_platos").hide();
                            $("#btn_platos_master").show();
                            $("#btn_modificar_platos").show();
                        }else{
                            $("#btn_deshabilitar_platos").hide();
                            $("#btn_habilitar_platos").show();
                            $("#btn_platos_master").hide();
                            $("#btn_modificar_platos").hide();
                        }
                    }else{
                        if(json.nivel === '0'){
                            $("#btn_platos_master").show();
                            $("#btn_sub_platos_master").show();
                            $("#btn_deshabilitar_platos").hide();
                            $("#btn_habilitar_platos").hide();
                            $("#btn_modificar_platos").show();
                        }else{
                            $("#btn_platos_master").hide();
                            $("#btn_sub_platos_master").hide();
                            $("#btn_deshabilitar_platos").hide();
                            $("#btn_habilitar_platos").hide();
                            $("#btn_modificar_platos").show();
                        }
                    }
                    $('#detalle').text(json.detalle);
                    if(json.estado === '1'){
                        $('#estado').text('HABILITADO');
                    }else{
                        $('#estado').text('NO HABILITADO');
                    }
                    if(json.tipo === '1'){
                        $('#tipo').text('PLATOS FINAL');
                    }else{
                        $('#tipo').text('INSUMOS');
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        function datosCategoriaInsumos(id){
            $.ajax({
                type: 'GET',
                url: '/categorias/get_datos_categoria_insumos/'+id,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(json){
                    $('#categoria_insumo_id').val(json.id);
                    $('#nombre_insumo').text(json.nombre);
                    $('#codigo_insumo').text(json.codigo);
                    $('#numeracion_insumo').text(json.numeracion);
                    $('#nivel_insumo').text(json.nivel);
                    if(json.nivel === '1'){
                        $("#btn_insumos_master").show();
                        $("#btn_sub_insumos_master").hide();
                        $("#btn_modificar_insumos").show();
                        if(json.estado === '1'){
                            $("#btn_deshabilitar_insumos").show();
                            $("#btn_habilitar_insumos").hide();
                            $("#btn_insumos_master").show();
                            $("#btn_modificar_insumos").show();
                        }else{
                            $("#btn_deshabilitar_insumos").hide();
                            $("#btn_habilitar_insumos").show();
                            $("#btn_insumos_master").hide();
                            $("#btn_modificar_insumos").hide();
                        }
                    }else{
                        if(json.nivel === '0'){
                            $("#btn_insumos_master").show();
                            $("#btn_sub_insumos_master").show();
                            $("#btn_deshabilitar_insumos").hide();
                            $("#btn_habilitar_insumos").hide();
                            $("#btn_modificar_insumos").show();
                        }else{
                            $("#btn_insumos_master").hide();
                            $("#btn_sub_insumos_master").hide();
                            $("#btn_deshabilitar_insumos").hide();
                            $("#btn_habilitar_insumos").hide();
                            $("#btn_modificar_insumos").show();
                        }
                    }
                    $('#detalle_insumo').text(json.detalle);
                    if(json.estado === '1'){
                        $('#estado_insumo').text('HABILITADO');
                    }else{
                        $('#estado_insumo').text('NO HABILITADO');
                    }
                    if(json.tipo === '1'){
                        $('#tipo_insumo').text('PLATOS FINAL');
                    }else{
                        $('#tipo_insumo').text('INSUMOS');
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        $("#toggleSubMenu").click(function(){
            $("#subMenuCategorias").slideToggle(250);
        });

        function crear_platos(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#categoria_id").val();
            var url = "{{ route('categorias.create_platos',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function crear_platos_master(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var url = "{{ route('categorias.create_platos_master',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function modificar(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#categoria_id").val();
            var url = "{{ route('categorias.editar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function habilitar_platos(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#categoria_id").val();
            var url = "{{ route('categorias.habilitar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function deshabilitar_platos(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#categoria_id").val();
            var url = "{{ route('categorias.deshabilitar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function crear_insumos(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#categoria_insumo_id").val();
            var url = "{{ route('categorias.create_insumos',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function crear_insumos_master(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var url = "{{ route('categorias.create_insumos_master',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function modificar_insumos(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#categoria_insumo_id").val();
            var url = "{{ route('categorias.editar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function habilitar_insumos(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#categoria_insumo_id").val();
            var url = "{{ route('categorias.habilitar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function deshabilitar_insumos(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#categoria_insumo_id").val();
            var url = "{{ route('categorias.deshabilitar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function solo_habilitados_platos(){
            $(".btn").hide();
            $(".empresa-id-select-container").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var status_platos = '1';
            var status_insumos = '[]';
            var url = "{{ route('categorias.index',[':id',':status_platos',':status_insumos']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status_platos',status_platos);
            url = url.replace(':status_insumos',status_insumos);
            window.location.href = url;
        }

        function todos_los_platos(){
            $(".btn").hide();
            $(".empresa-id-select-container").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var status_platos = '[]';
            var status_insumos = '[]';
            var url = "{{ route('categorias.index',[':id',':status_platos',':status_insumos']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status_platos',status_platos);
            url = url.replace(':status_insumos',status_insumos);
            window.location.href = url;
        }

        function solo_habilitados_insumos(){
            $(".btn").hide();
            $(".empresa-id-select-container").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var status_platos = '[]';
            var status_insumos = '1';
            var url = "{{ route('categorias.index',[':id',':status_platos',':status_insumos']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status_platos',status_platos);
            url = url.replace(':status_insumos',status_insumos);
            window.location.href = url;
        }

        function todos_los_insumos(){
            $(".btn").hide();
            $(".empresa-id-select-container").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var status_platos = '[]';
            var status_insumos = '[]';
            var url = "{{ route('categorias.index',[':id',':status_platos',':status_insumos']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status_platos',status_platos);
            url = url.replace(':status_insumos',status_insumos);
            window.location.href = url;
        }
    </script>
@stop
