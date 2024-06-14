<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('empresas.partials.search')
    @include('empresas.partials.table')
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
                procesar();
                event.preventDefault();
            }
        });

        function create(){
            var id = $("#pi_cliente_id").val();
            var url = "{{ route('empresas.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function retroceder(){
            var url = "{{ route('pi.clientes.index') }}";
            window.location.href = url;
        }

        function limpiar(){
            var id = $("#pi_cliente_id").val();
            var url = "{{ route('empresas.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function procesar(){
            var id = $("#pi_cliente_id").val();
            var url = "{{ route('empresas.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function valideNumberInteger(evt){
            var code = (evt.which) ? evt.which : evt.keyCode;
            if(code>=48 && code<=57){
                return true;
            }else{
                return false;
            }
        }
    </script>
@stop
