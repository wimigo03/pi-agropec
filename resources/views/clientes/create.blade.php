@extends('adminlte::page')
@section('title', 'Personal')
@section('content')
<br>
<div class="card card-custom">
    <div class="card-header font-verdana-bg">
        <b>REGISTRAR PERSONAL</b>
    </div>
    <div class="card-body">
        <form action="#" method="post" id="form">
            @csrf
            @include('personal.partials.form-create')
        </form>
        {{--<div class="form-group row">
            <div class="col-md-12 text-right">
                <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                    <i class="fas fa-paper-plane"></i>&nbsp;Procesar
                </button>
                <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
                    &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
                </button>
                <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
            </div>
        </div>--}}
    </div>
</div>
@endsection
@section('css')
    @parent
@stop
@section('js')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%'
            });
        });

        function datos_laborales() {
            $("#datos_personales").hide();
            $("#btn_datos_laborales").hide();
        }

        /*function procesar() {
            var url = "{{ route('cargos.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            window.location.href = "{{ route('cargos.index') }}";
        }*/
    </script>
@stop