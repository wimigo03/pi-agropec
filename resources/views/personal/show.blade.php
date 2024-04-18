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
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header font-verdana-bg bg-info text-white">
                <b>DETALLE PERSONAL - {{ $empresa->nombre_comercial }}</b>
            </div>
            <div class="card-body">
                <input type="hidden" value="{{ $empresa->id }}" id="empresa_id">
                @include('personal.partials.form-show')
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
                            &nbsp;<i class="fas fa-times"></i>&nbsp;Ir a Listado
                        </button>
                        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
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

            document.getElementById('edad').value = calcularEdad($("#fecha_nac").val());
            
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

            if($('#checkboxTree').prop('checked')) {
                $('#collapseTree').collapse('show');
            }else{
                $('#collapseTree').collapse('hide');
            }
        });

        function calcularEdad(fechaNacimiento) {
            var fx = fechaNacimiento.substring(6,10) + "-" + fechaNacimiento.substring(3,5) + "-" + fechaNacimiento.substring(0,2);
            var fechaNace = new Date(fx);
            var fechaActual = new Date();
            var mes = fechaActual.getMonth();
            var dia = fechaActual.getDay();
            var ano = fechaActual.getFullYear();
            fechaActual.setDate(dia);
            fechaActual.setMonth(mes);
            fechaActual.setFullYear(ano);
            return edad = Math.floor(((fechaActual - fechaNace) / (1000 * 60 * 60 * 24) / 365));
        }

        function cancelar(){
            $(".btn").hide();            
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('personal.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop