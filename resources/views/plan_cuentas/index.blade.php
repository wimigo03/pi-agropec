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
</style>
@section('content')
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-1 pl-1">
            <form action="#" method="get" id="form_estructura">
                <select name="empresa_id" id="empresa_id" class="form-control">
                    <option value="">-</option>
                    @foreach ($empresas as $index => $value)
                        <option value="{{ $index }}" @if(isset($empresa_id) ? $empresa_id : request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    @include('plan_cuentas.partials.treeview')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $("#btn_deshabilitar").hide();
            $("#btn_habilitar").hide();
            $("#btn_sub_master").hide();
            $("#btn_modificar").hide();

            var plancuenta_id = $('#plancuenta_id').val();
            var empresa_id = $("#empresa_id >option:selected").val();

            if ($('#plancuenta_id option[value="' + plancuenta_id + '"]').length !== 0) {
                if($('#plancuenta_id').val() !== '#'){
                    datosPlanCuenta(empresa_id,document.getElementById('plancuenta_id').value);
                }
            }

            $('#empresa_id').select2({
                theme: "bootstrap4",
                placeholder: "--Empresa--",
                width: '100%'
            });
        });

        $('#empresa_id').change(function() {
            var id = $(this).val();
            planCuentasByEmpresa(id);
        });

        function planCuentasByEmpresa(id){
            var status = '[]';
            var url = "{{ route('plan_cuentas.index',[':id',':status']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status',status);
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
                var empresa_id = $("#empresa_id >option:selected").val();
                datosPlanCuenta(empresa_id,data.node.id);
            });

            $('#treeview').on('ready.jstree', function () {
                $('#treeview').jstree('select_node', nodo_id);
                if(nodo_id != ''){
                    var empresa_id = $("#empresa_id >option:selected").val();
                    datosPlanCuenta(empresa_id,nodo_id);
                }
            });
        });

        function datosPlanCuenta(empresa_id,id){
            $.ajax({
                type: 'GET',
                url: '/plan-cuentas/get_datos_plan_cuenta/'+empresa_id+'/'+id,
                dataType: 'json',
                data: {
                    id: id,
                    empresa_id: empresa_id
                },
                success: function(json){
                    $('#plancuenta_id').val(json.id);
                    $('#nombre').text(json.nombre);
                    $('#codigo').text(json.codigo);
                    $('#nivel').text(json.nivel);
                    if(json.estado === '1'){
                        $("#btn_deshabilitar").show();
                        $("#btn_habilitar").hide();
                        $("#btn_master").show();
                        $("#btn_sub_master").show();
                        $("#btn_modificar").show();
                    }else{
                        $("#btn_deshabilitar").hide();
                        $("#btn_habilitar").show();
                        $("#btn_sub_master").hide();
                        $("#btn_modificar").hide();
                    }
                    if(json.detalle === '1'){
                        $("#btn_sub_master").hide();
                        $('#detalle').text('SI ES CUENTA DETALLE');
                    }else{
                        $("#btn_sub_master").show();
                        $('#detalle').text('NO ES CUENTA DETALLE');
                    }
                    if(json.banco === '1'){
                        $('#banco').text('SI ES UNA CUENTA DE BANCO');
                    }else{
                        $('#banco').text('NO ES UNA CUENTA DE BANCO');
                    }
                    if(json.auxiliar === '1'){
                        $('#auxiliar').text('SI TIENE AUXILIAR');
                    }else{
                        $('#auxiliar').text('NO TIENE AUXILIAR');
                    }
                    if(json.estado === '1'){
                        $('#estado').text('HABILITADO');
                    }else{
                        $('#estado').text('NO HABILITADO');
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        function create(){
            var id = $("#empresa_id").val()
            var url = "{{ route('plan_cuentas.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function createSub(){
            var empresa_id = $("#empresa_id >option:selected").val();
            var id = $("#plancuenta_id").val();
            var url = "{{ route('plan_cuentas.create_sub',['empresa_id' => ':empresa_id', 'id' => ':id']) }}";
            url = url.replace(':empresa_id',empresa_id);
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function editar(){
            var empresa_id = $("#empresa_id >option:selected").val();
            var id = $("#plancuenta_id").val();
            var url = "{{ route('plan_cuentas.editar',['empresa_id' => ':empresa_id', 'id' => ':id']) }}";
            url = url.replace(':empresa_id',empresa_id);
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function habilitar(){
            var empresa_id = $("#empresa_id >option:selected").val();
            var id = $("#plancuenta_id").val();
            var url = "{{ route('plan_cuentas.habilitar',['empresa_id' => ':empresa_id', 'id' => ':id']) }}";
            url = url.replace(':empresa_id',empresa_id);
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function deshabilitar(){
            var empresa_id = $("#empresa_id >option:selected").val();
            var id = $("#plancuenta_id").val();
            var url = "{{ route('plan_cuentas.deshabilitar',['empresa_id' => ':empresa_id', 'id' => ':id']) }}";
            url = url.replace(':empresa_id',empresa_id);
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function solo_habilitados(){
            $(".btn").hide();
            $(".empresa-id-select-container").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var status = '1';
            var url = "{{ route('plan_cuentas.index',[':id',':status']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status',status);
            window.location.href = url;
        }

        function todos(){
            $(".btn").hide();
            $(".empresa-id-select-container").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var status = '[]';
            var url = "{{ route('plan_cuentas.index',[':id',':status']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status',status);
            window.location.href = url;
        }
    </script>
@stop
