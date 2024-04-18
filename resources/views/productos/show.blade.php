<!DOCTYPE html>
@extends('layouts.dashboard')
<style>
    .select2 + .select2-container .select2-selection__rendered {
        font-size: 13px;
    }
    .select2-results__option {
        font-size: 13px;
    }
</style>
@section('content')
    @include('productos.partials.menu')
    @include('productos.partials.form-show')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $("#subMenuProductos").hide();
        });

        $("#toggleSubMenu").click(function(){
            $("#subMenuProductos").slideToggle(250);
        });
        
        function pdf(){
            var id = $("#producto_id").val()
            var url = "{{ route('productos.pdf',':id') }}";
            url = url.replace(':id',id);
            window.open(url, '_blank');
        }

        function index(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('productos.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop