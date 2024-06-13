<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('users.partials.search')
    @include('users.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('#cargo_id').select2({
                theme: "bootstrap4",
                placeholder: "--Cargo--",
                width: '100%'
            });

            $('#role_id').select2({
                theme: "bootstrap4",
                placeholder: "--Role--",
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

        function search(){
            var id = $("#empresa_id").val();
            var url = "{{ route('users.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            localStorage.clear();
            var id = $("#empresa_id").val();
            var url = "{{ route('users.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
