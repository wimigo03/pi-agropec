<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('tipo_precios.partials.menu')
    {{--@include('tipo_precios.partials.search')--}}
    <div class="form-group row">
        <div class="col-md-12">
            @can('tipo.precios.create')
                <span class="tts:right tts-slideIn tts-custom" aria-label="Crear Tipo Precio" style="cursor: pointer;">
                    <button class="btn btn-outline-success font-verdana" type="button" onclick="create();">
                        <i class="fas fa-plus fa-fw"></i>
                    </button>
                </span>
            @endcan
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
        {{--<div class="col-md-6 px-0 pl-1 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="search();">
                &nbsp;<i class="fas fa-search"></i>&nbsp;Buscar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
                &nbsp;<i class="fas fa-eraser"></i>&nbsp;Limpiar
            </button>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>--}}
    </div>
    @include('tipo_precios.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
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

        $("#toggleSubMenu").click(function(){
            $("#subMenuPrecioVentas").slideToggle(250);
        });

        function create(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var empresa_id = $("#empresa_id").val();
            var url = "{{ route('tipo.precios.create',[':empresa_id']) }}";
            url = url.replace(':empresa_id',empresa_id);
            window.location.href = url;
        }

        /*function search(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('tipo.precios.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('tipo.precios.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }*/
    </script>
@stop