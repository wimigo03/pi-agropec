@extends('adminlte::page')
@section('title', 'Cargos')
@section('content')
<br>
<div class="card card-custom">
    <div class="card-header font-verdana-bg">
        <b>MODIFICAR CARGOS</b>
    </div>
    <div class="card-body">
        <form action="#" method="post" id="form">
            @csrf
            <input type="hidden" name="cargo_id" value="{{ $cargo->id }}">
            @include('cargos.partials.form-editar')
        </form>
        <div class="form-group row">
            <div class="col-md-12 text-right">
                <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                    <i class="fas fa-paper-plane"></i>&nbsp;Actualizar
                </button>
                <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
                    &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
                </button>
                <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
            </div>
        </div>
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

        function procesar() {
            var url = "{{ route('cargos.update') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            window.location.href = "{{ route('cargos.index') }}";
        }
    </script>
@stop