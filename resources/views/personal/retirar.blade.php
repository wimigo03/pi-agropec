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
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header font-verdana-bg bg-gradient-warning text-white">
                <b>RETIRAR PERSONAL - CONTRATO {{ App\Models\PersonalContrato::TIPOS[$tipo] }}</b>
            </div>
            <div class="card-body">
                <form action="#" method="post" id="form">
                    @csrf
                    <input type="hidden" name="personal_laboral_id" value="{{ $personal_laboral->id }}" id="personal_laboral_id">
                    <input type="hidden" name="personal_id" value="{{ $personal->id }}" id="personal_id">
                    <input type="hidden" name="tipo" value="{{ $tipo }}" id="tipo">
                    <input type="hidden" value="{{ $empresa->id }}" id="empresa_id">
                    <div class="form-group row pr-1 abs-center font-verdana-bg">
                        <div class="col-md-8">
                            <h1><strong>{{ $personal->full_name }}</strong></h1>
                        </div>
                    </div>
                    <div class="form-group row abs-center">
                        <div class="col-md-8 pr-1 font-verdana-bg">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="radio" name="tipo_retiro" value="RENUNCIA" {{ old('tipo_retiro') == 'RENUNCIA' ? 'checked' : '' }}>&nbsp;<b>RENUNCIA</b>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" name="tipo_retiro" value="DESPIDO" {{ old('tipo_retiro') == 'DESPIDO' ? 'checked' : '' }}>&nbsp;<b>DESPIDO</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row abs-center">
                        <div class="col-md-8 pr-1 font-verdana-bg">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="fecha_retiro" value="{{ old('fecha_retiro') }}" placeholder="--Fecha Retiro--" id="fecha_retiro" class="form-control font-verdana-bg intro" data-language="es" readonly>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="motivo_retiro" value="{{ old('motivo_retiro') }}" placeholder="--Motivo Retiro--" id="motivo_retiro" class="form-control font-verdana-bg" oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
            $("#fecha_retiro").datepicker({
                inline: false,
                dateFormat: "dd/mm/yy",
                autoClose: true
            });
        });

        function alertaModal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
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
            var opciones = document.getElementsByName('tipo_retiro');
            var alMenosUnoSeleccionado = false;

            for (var i = 0; i < opciones.length; i++) {
                if (opciones[i].checked) {
                    alMenosUnoSeleccionado = true;
                    break;
                }
            }

            if (!alMenosUnoSeleccionado) {
                alertaModal("<center>Tiene que seleccionar al menos un tipo de retiro...</center>");
                return false;
            }

            if($("#fecha_retiro").val() == ""){
                alertaModal("<center>La <b>[FECHA DE RETIRO]</b> es un dato obligatorio...</center>");
                return false;
            }
            return true;
        }

        function confirmar(){
            var url = "{{ route('personal.destroy') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
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
