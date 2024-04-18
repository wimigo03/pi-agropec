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
    @include('productos.partials.form-editar')
    <div class="form-group row">
        <div class="col-md-12 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                <i class="fas fa-paper-plane"></i>&nbsp;Procesar
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

            verificarObligatorio();
        });

        function alertaModal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        $('#categoria_master_id').change(function() {
            var id = $(this).val();
            getCodigoMaster(id);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            getCategorias(id,CSRF_TOKEN);
        });

        function getCodigoMaster(id){
            $.ajax({
                type: 'GET',
                url: '/productos/get_codigo_master/'+id,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(json){
                    $('#codigo').val(json.codigo);
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        function getCategorias(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/categorias/get_datos_subcategorias',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){
                    if(data.subcategorias){
                        var arr = Object.values($.parseJSON(data.subcategorias));
                        $("#categoria_id").empty();
                        var select = $("#categoria_id");
                        select.append($("<option></option>").attr("value", '').text('--Categoria--'));
                        var categoriaIdSeleccionado = localStorage.getItem('categoriaIdSeleccionado');
                        $.each(arr, function(index, json) {
                            var opcion = $("<option></option>").attr("value", json.id).text(json.nombre);
                            if (json.id == categoriaIdSeleccionado) {
                                opcion.attr('selected', 'selected');
                            }
                            select.append(opcion);
                        });
                        select.on('change', function() {
                            localStorage.setItem('categoriaIdSeleccionado', $(this).val());
                        });
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }
        
        $('#categoria_id').change(function() {
            var id = $(this).val();
            if($("#producto_categoria_id").val() === id){
                alertaModal("<b>[ERROR]</b> . No puede seleccionar la misma categoria anterior");
                document.getElementById('codigo').value = '';
            }else{
                getCodigo(id);
            }
        });

        function verificarObligatorio(){
            if($("#categoria_master_id").val() !== ""){
                $("#obligatorio_categoria_master_id").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_categoria_master_id").addClass('select2-container--obligatorio');
            }

            if($("#categoria_id >option:selected").val() !== ""){
                $("#obligatorio_categoria_id").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_categoria_id").addClass('select2-container--obligatorio');
            }

            if($("#nombre").val() !== ""){
                $("#nombre").removeClass('obligatorio');
            }else{
                $("#nombre").addClass('obligatorio');
            }

            if($("#nombre_factura").val() !== ""){
                $("#nombre_factura").removeClass('obligatorio');
            }else{
                $("#nombre_factura").addClass('obligatorio');
            }

            if($("#codigo").val() !== ""){
                $("#codigo").removeClass('obligatorio');
            }else{
                $("#codigo").addClass('obligatorio');
            }

            if($("#unidad_id >option:selected").val() !== ""){
                $("#obligatorio_unidad_id").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_unidad_id").addClass('select2-container--obligatorio');
            }

            if($("#detalle").val() !== ""){
                $("#detalle").removeClass('obligatorio');
            }else{
                $("#detalle").addClass('obligatorio');
            }
        }

        function getCodigo(id){
            $.ajax({
                type: 'GET',
                url: '/productos/get_codigo/'+id,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(json){
                    $('#codigo').val(json.codigo);
                    $('#categoria_master').val(json.categoria_master);
                    $('#categoria_master_id').val(json.categoria_master_id);
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

        function procesar() {
            if(!validarDatos()){
                return false;
            }
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function validarDatos(){
            if($("#categoria_id >option:selected").val() == ""){
                alertaModal("<center>La <b>[CATEGORIA]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#nombre").val() == ""){
                alertaModal("<center>El <b>[NOMBRE]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#nombre_factura").val() == ""){
                alertaModal("<center>El <b>[NOMBRE DE LA FACTURA]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#codigo").val() == ""){
                alertaModal("<center>El <b>[CODIGO]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#detalle").val() == ""){
                alertaModal("<center>El <b>[DETALLE]</b> es un dato obligatorio...</center>");
                return false;
            }
            return true;
        }

        function confirmar(){
            var url = "{{ route('productos.update') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();            
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('productos.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop