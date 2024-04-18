<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('roles.partials.search')
    @include('roles.partials.table')
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

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                search();
                event.preventDefault();
            }
        });

        function search(){
            var id = $("#empresa_id").val();
            var url = "{{ route('roles.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('roles.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function create(){
            var id = $("#empresa_id").val()
            var url = "{{ route('roles.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
