<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('asientos_automaticos.partials.form-show')
    <div class="form-group row">
        <div class="col-md-12 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="cancelar();">
                &nbsp;<i class="fas fa-times fa-fw"></i>&nbsp;Ir a listado
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
        });

        function Modal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function cancelar(){
            var url = "{{ route('asiento.automatico.index') }}";
            window.location.href = url;
        }
    </script>
@stop
