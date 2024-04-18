<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
@include('cargos.partials.form-create')
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

            if($("#tipo >option:selected").val() == "1"){
                $("#cuenta_contable").show();
            }else{
                $("#cuenta_contable").hide();
            }
        });

        $('#tipo').on('change', function() {
            if($("#tipo >option:selected").val() == "1"){
                $("#cuenta_contable").show();
            }else{
                $("#cuenta_contable").hide();
            }
        });

        function procesar() {
            var url = "{{ route('cargos.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('cargos.index',[':id']) }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop