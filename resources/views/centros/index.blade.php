<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('centros.partials.search')
    @include('centros.partials.table')
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

            $('#empresa_id').select2({
                theme: "bootstrap4",
                placeholder: "--Empresa--",
                width: '100%'
            });

            $('#centro').select2({
                theme: "bootstrap4",
                placeholder: "--Centro--",
                width: '100%'
            });

            $('#sub_centro').select2({
                theme: "bootstrap4",
                placeholder: "--SubCentro--",
                width: '100%'
            });

            $('#tipo').select2({
                theme: "bootstrap4",
                placeholder: "--Tipo--",
                width: '100%'
            });

            var cleave = new Cleave('#fecha', {
                date: true,
                datePattern: ['d', 'm', 'Y']
            });

            $("#fecha").datepicker({
                inline: false,
                dateFormat: "dd-mm-yy",
                autoClose: true,
                delimiter: '-'
            });

            $('#estado').select2({
                theme: "bootstrap4",
                placeholder: "--Estado--",
                width: '100%'
            });
        });

        function create(){
            var url = "{{ route('centros.create') }}";
            window.location.href = url;
        }

        function subcreate(){
            var url = "{{ route('sub.centros.create') }}";
            window.location.href = url;
        }

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                search();
                event.preventDefault();
            }
        });

        function search(){
            var url = "{{ route('centros.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function limpiar(){
            var url = "{{ route('centros.index') }}";
            window.location.href = url;
        }
    </script>
@stop
