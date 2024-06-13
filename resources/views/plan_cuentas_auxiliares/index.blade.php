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
    @include('plan_cuentas_auxiliares.partials.search')
    @include('plan_cuentas_auxiliares.partials.table')
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

            $('#estado').select2({
                theme: "bootstrap4",
                placeholder: "--Estado--",
                width: '100%'
            });

            $('#tipo').select2({
                theme: "bootstrap4",
                placeholder: "--Tipo--",
                width: '100%'
            });
        });

        function create(){
            var url = "{{ route('plan_cuentas.auxiliar.create') }}";
            window.location.href = url;
        }

        function search(){
            var url = "{{ route('plan_cuentas.auxiliar.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function limpiar(){
            var url = "{{ route('plan_cuentas.auxiliar.index') }}";
            window.location.href = url;
        }
    </script>
@stop
