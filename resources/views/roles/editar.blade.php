<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('roles.partials.form-editar')
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

        function marcarTodo(id) {
            input = document.getElementById(id);
            checks = document.getElementsByClassName(id);
            if (input.value == 0) {
                for (let index = 0; index < checks.length; index++) {
                    checks[index].checked = true;
                }
                input.value = 1;
            }else{
                for (let index = 0; index < checks.length; index++) {
                    checks[index].checked = false;
                }
                input.value = 0;
            }
        }

        $('#modulo_id').change(function() {
            var id = $(this).val();
            search(id);
        });

        function search(){
            var id = $("#empresa_id").val();
            var url = "{{ route('roles.editar',':id') }}";
            $("#form_search").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form_search").submit();
        }
        function procesar() {
            var url = "{{ route('roles.update') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('roles.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
