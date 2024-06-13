<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('users.partials.form-editar')
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

        function procesar() {
            var url = "{{ route('users.update') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            localStorage.clear();
            var id = $("#empresa_id").val();
            var url = "{{ route('users.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
