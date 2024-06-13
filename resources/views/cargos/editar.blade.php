<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
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
                    <div class="col-md-12 pr-1 text-right">
                        <span class="btn btn-outline-primary font-roboto-12" onclick="procesar();">
                            <i class="fas fa-paper-plane"></i>&nbsp;Actualizar
                        </span>
                        <span class="btn btn-outline-danger font-roboto-12" onclick="cancelar();">
                            &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
                        </span>
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
            var id = $("#empresa_id").val();
            var url = "{{ route('cargos.index',[':id']) }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
