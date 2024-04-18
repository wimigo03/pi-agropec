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
        border-color: red  !important;
    }
</style>
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header font-verdana-bg bg-gradient-warning text-white">
                <b>SUBIR CONTRATO LABORAL</b>
            </div>
            <div class="card-body">
                <form action="#" method="post" id="form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="personal_laboral_id" value="{{ $personal_laboral->id }}" id="personal_laboral_id">
                    <input type="hidden" name="empresa_id" value="{{ $personal_laboral->empresa_id }}" id="empresa_id">
                    <div class="form-group row pr-1 abs-center font-verdana-bg">
                        <div class="col-md-6 text-center">
                            <h2><strong>{{ $personal->full_name }}</strong></h2>
                        </div>
                    </div>
                    <div class="form-group row abs-center">
                        <div class="col-md-6 pr-1 font-verdana-bg">
                            [Solo adminte formato .pdf]
                            <input type="file" name="file_contrato" class="form-control font-verdana-bg">
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
            
        });

        function alertaModal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function procesar() {
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function confirmar(){
            var url = "{{ route('personal.file.contrato.store') }}";
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