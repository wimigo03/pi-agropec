<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    {{--@include('asientos_automaticos.partials.search')--}}
    <div class="form-group row">
        <div class="col-md-12 px-1">
            @can('asiento.automatico.create')
                <span class="tts:right tts-slideIn tts-custom" aria-label="Crear Asiento" style="cursor: pointer;">
                    <span class="btn btn-outline-success font-roboto-12" onclick="create();">
                        <i class="fas fa-plus fa-fw"></i>
                    </span>
                </span>
            @endcan
            <span class="btn btn-outline-danger font-roboto-12 float-right" onclick="limpiar();">
                <i class="fas fa-eraser"></i>&nbsp;Limpiar
            </span>
            <span class="btn btn-outline-primary font-roboto-12 float-right mr-1" onclick="search();">
                <i class="fas fa-search"></i>&nbsp;Buscar
            </span>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
    @include('asientos_automaticos.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('#modulo_id').select2({
                theme: "bootstrap4",
                placeholder: "--Modulos--",
                width: '100%'
            });

            $('#plan_cuenta_id').select2({
                theme: "bootstrap4",
                placeholder: "--Cuenta Contable--",
                width: '100%'
            });

            $('#tipo').select2({
                theme: "bootstrap4",
                placeholder: "--Tipo--",
                width: '100%'
            });

            $('#copia').select2({
                theme: "bootstrap4",
                placeholder: "--Copia--",
                width: '100%'
            });

            $('#estado').select2({
                theme: "bootstrap4",
                placeholder: "--Estado--",
                width: '100%'
            });
        });

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                search();
                event.preventDefault();
            }
        });

        function create(){
            var url = "{{ route('asiento.automatico.create') }}";
            window.location.href = url;
        }

        function search(){
            var url = "{{ route('asiento.automatico.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function limpiar(){
            var url = "{{ route('asiento.automatico.index') }}";
            window.location.href = url;
        }
    </script>
@stop

