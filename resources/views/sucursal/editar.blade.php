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
        border-color: red;
    }
</style>
@section('content')
    @include('sucursal.partials.form-editar')
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.nav-link.active').addClass('font-weight-bold');
            $('.nav-link').on('shown.bs.tab', function (e) {
                $('.nav-link').removeClass('font-weight-bold');
                $(e.target).addClass('font-weight-bold');
            });

            $('#myTabs a').on('click', function (e) {
                e.preventDefault()
                $(this).tab('show')
            })

            $('.select2').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%'
            });

            verificarObligatorio();

            if($('#checkboxOne').prop('checked')) {
                $('#collapseOne').collapse('show');
            }else{
                $('#collapseOne').collapse('hide');
            }

            if($('#checkboxTwo').prop('checked')) {
                $('#collapseTwo').collapse('show');
            }else{
                $('#collapseTwo').collapse('hide');
            }
        });

        $('#checkboxOne').change(function () {
            if ($(this).prop('checked')) {
                $('#collapseOne').collapse('show');
            } else {
                $('#collapseOne').collapse('hide');
            }
        });

        $('#checkboxTwo').change(function () {
            if ($(this).prop('checked')) {
                $('#collapseTwo').collapse('show');
            } else {
                $('#collapseTwo').collapse('hide');
            }
        });

        function alertaModal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
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

        function verificarObligatorio(){
            if($("#nombre").val() !== ""){
                $("#nombre").removeClass('obligatorio');
            }else{
                $("#nombre").addClass('obligatorio');
            }

            if($("#ciudad").val() !== ""){
                $("#ciudad").removeClass('obligatorio');
            }else{
                $("#ciudad").addClass('obligatorio');
            }

            if($("#direccion").val() !== ""){
                $("#direccion").removeClass('obligatorio');
            }else{
                $("#direccion").addClass('obligatorio');
            }
        }

        function procesar() {
            if(!validarDatosGenerales()){
                return false;
            }
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function validarDatosGenerales(){
            if($("#empresa").val() == ""){
                alertaModal("<center>El campo en <u>Datos Generales</u><br><b>[Empresa]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#nombre").val() == ""){
                alertaModal("<center>El campo en <u>Datos Generales</u><br><b>[Nombre]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#ciudad").val() == ""){
                alertaModal("<center>El campo en <u>Datos Generales</u><br><b>[Celular]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#direccion").val() == ""){
                alertaModal("<center>El campo en <u>Datos Generales</u><br><b>[Direccion]</b> es un dato obligatorio...</center>");
                return false;
            }
            return true;
        }

        function confirmar(){
            var url = "{{ route('sucursal.update') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('sucursal.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
