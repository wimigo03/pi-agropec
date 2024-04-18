<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('permissions.partials.form-create')
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-2 px-0 pr-1">
            <button class="btn btn-block btn-outline-primary font-verdana" type="button" onclick="procesar();">
                <i class="fas fa-paper-plane"></i>&nbsp;Procesar
            </button>
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <button class="btn btn-block btn-outline-danger font-verdana" type="button" onclick="cancelar();">
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
            $("#form_nuevo_titulo").hide();
            if($("#titulo >option:selected").val() === "_NUEVO_"){
                $("#form_nuevo_titulo").show();
            }else{
                $("#form_nuevo_titulo").hide();
                document.getElementById('nuevo_titulo').value = '';
            }

            $('.select2').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%'
            });
        });

        $('#titulo').on('change', function() {
                if($("#titulo >option:selected").val() === "_NUEVO_"){
                    $("#form_nuevo_titulo").show();
                }else{
                    $("#form_nuevo_titulo").hide();
                    document.getElementById('nuevo_titulo').value = '';
                }
            }
        );

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                procesar();
                event.preventDefault();
            }
        });

        function procesar() {
            var url = "{{ route('permissions.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('permissions.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
