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
</style>
@section('content')
    @include('categorias.partials.menu')
    <form action="#" method="post" id="form">
        @csrf
        @include('categorias.partials.form-create-master')
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
            $("#subMenuCategorias").hide();
        });

        $("#toggleSubMenu").click(function(){
            $("#subMenuCategorias").slideToggle(250);
        });

        function procesar() {
            var url = "{{ route('categorias.store.master') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }
        
        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var status_platos = '[]';
            var status_insumos = '[]';
            var url = "{{ route('categorias.index',[':id',':status_platos',':status_insumos']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status_platos',status_platos);
            url = url.replace(':status_insumos',status_insumos);
            window.location.href = url;
        }
    </script>
@stop