<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('balance_apertura.partials.search')
    @if (count($balances) > 0)
        @include('balance_apertura.partials.table')
    @endif
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

        }

        function cambiarf(){
            var id = $("#empresa_id").val();
            var url = "{{ route('balance.apertura.f.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function limpiar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('balance.apertura.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function create(){
            var id = $("#empresa_id").val()
            var url = "{{ route('balance.apertura.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
