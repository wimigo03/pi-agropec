<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    {{--@include('asientos_automaticos.partials.search')--}}
    <div class="form-group row">
        <div class="col-md-6 px-0 pr-1">
            @can('asiento.automatico.create')
                <span class="tts:right tts-slideIn tts-custom" aria-label="Crear Asiento" style="cursor: pointer;">
                    <button class="btn btn-outline-success font-verdana" type="button" onclick="create();">
                        <i class="fas fa-plus fa-fw"></i>
                    </button>
                </span>
            @endcan
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
        <div class="col-md-6 px-0 pl-1 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="search();">
                &nbsp;<i class="fas fa-search"></i>&nbsp;Buscar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
                &nbsp;<i class="fas fa-eraser"></i>&nbsp;Limpiar
            </button>
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
            var id = $("#empresa_id").val()
            var url = "{{ route('asiento.automatico.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function search(){
            var id = $("#empresa_id").val();
            var url = "{{ route('asiento.automatico.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('asiento.automatico.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop

