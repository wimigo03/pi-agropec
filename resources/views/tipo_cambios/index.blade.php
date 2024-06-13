<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('tipo_cambios.partials.search')
    @include('tipo_cambios.partials.table')
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

            var cleave = new Cleave('#fecha_i', {
                date: true,
                datePattern: ['d', 'm', 'Y'],
                delimiter: '-'
            });

            var cleave = new Cleave('#fecha_f', {
                date: true,
                datePattern: ['d', 'm', 'Y'],
                delimiter: '-'
            });

            $("#fecha_i").datepicker({
                inline: false,
                dateFormat: "dd-mm-yy",
                autoClose: true,
            });

            $("#fecha_f").datepicker({
                inline: false,
                dateFormat: "dd-mm-yy",
                autoClose: true,
            });
        });

        function countCharsI(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha_i").value;
                if(!validarFecha(date)){
                    document.getElementById('fecha_i').value = '';
                }
            }
        }

        function countCharsF(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha_f").value;
                if(!validarFecha(date)){
                    document.getElementById('fecha_f').value = '';
                }
            }
        }

        function validarFecha(date) {
            var regexFecha = /^\d{2}\/\d{2}\/\d{4}$/;
            if (regexFecha.test(date)) {
                var partesFecha = date.split('/');
                var dia = parseInt(partesFecha[0], 10);
                var mes = parseInt(partesFecha[1], 10);
                var anio = parseInt(partesFecha[2], 10);
                if (dia >= 1 && dia <= 31 && mes >= 1 && mes <= 12 && anio >= 1900) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function create(){
            var url = "{{ route('tipo.cambio.create') }}";
            window.location.href = url;
        }

        function search(){
            var url = "{{ route('tipo.cambio.search') }}";
            $("#form").attr('action', url);
            $("#form").submit();
        }

        function limpiar(){
            var url = "{{ route('tipo.cambio.index') }}";
            window.location.href = url;
        }
    </script>
@stop
