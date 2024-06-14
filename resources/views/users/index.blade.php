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
            $('#empresa_id').select2({
                theme: "bootstrap4",
                placeholder: "--Empresa--",
                width: '100%'
            });

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
            var url = "{{ route('users.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function limpiar(){
            localStorage.clear();
            var url = "{{ route('users.index') }}";
            window.location.href = url;
        }
    </script>
@stop
