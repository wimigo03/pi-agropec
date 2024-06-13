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
</style>
@section('content')
    @include('plan_cuentas.partials.form-editar-sub')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            var checkbox_detalle = document.getElementById("detalle");
            if (checkbox_detalle.checked) {
                $('#cuenta_banco').show();
            } else {
                $('#cuenta_banco').hide();
            }

            $('.select2').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%'
            });

            obligatorio();
        });

        $('#detalle').change(function () {
            var checkbox_detalle = document.getElementById("detalle");
            if (checkbox_detalle.checked) {
                $('#cuenta_banco').show();
            } else {
                $('#cuenta_banco').hide();
            }
        });

        function obligatorio(){
            if($("#nombre").val() !== ""){
                $("#nombre").removeClass('obligatorio');
            }else{
                $("#nombre").addClass('obligatorio');
            }
        }

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                procesar();
                event.preventDefault();
            }
        });

        function procesar() {
            var url = "{{ route('plan_cuentas.update') }}";
            $("#form").attr('action', url);
            $(".btns").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btns").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var status = '[]';
            var url = "{{ route('plan_cuentas.index',[':id',':status']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status',status);
            window.location.href = url;
        }

        function limpiar(){
            var empresa_id = $("#empresa_id").val();
            var id = $("#plancuenta_id").val();
            var url = "{{ route('plan_cuentas.editar',['empresa_id' => ':empresa_id', 'id' => ':id']) }}";
            url = url.replace(':empresa_id',empresa_id);
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
