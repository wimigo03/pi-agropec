<!DOCTYPE html>
@extends('layouts.dashboard')
<style>
    .select2 + .select2-container .select2-selection__rendered {
        font-size: 11px;
    }
    .select2-results__option {
        font-size: 13px;
    }
</style>
@section('content')
    @include('permissions.partials.search')
    @include('permissions.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('#modulo_id').select2({
                theme: "bootstrap4",
                placeholder: "--Modulo--",
                width: '100%'
            });

            $('#titulo').select2({
                theme: "bootstrap4",
                placeholder: "--Titulo--",
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
            var url = "{{ route('permissions.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('permissions.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function create(){
            var id = $("#empresa_id").val()
            var url = "{{ route('permissions.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
