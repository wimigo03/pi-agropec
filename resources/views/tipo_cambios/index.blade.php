<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('tipo_cambios.partials.search')
    <div class="form-group row">
        <div class="col-md-6 px-0 pr-1">
            @can('tipo.cambio.create')
                <button class="btn btn-outline-success font-verdana" type="button" onclick="create();">
                    <i class="fas fa-plus fa-fw"></i>
                </button>
            @endcan
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
        <div class="col-md-6 px-0 pl-1 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="search();">
                <i class="fas fa-search fa-fw"></i>&nbsp;Buscar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
                <i class="fas fa-eraser fa-fw"></i>&nbsp;Limpiar
            </button>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
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

            $("#fecha_i").datepicker({
                inline: false,
                dateFormat: "dd/mm/yy",
                autoClose: true,
            });

            $("#fecha_f").datepicker({
                inline: false,
                dateFormat: "dd/mm/yy",
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
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var url = "{{ route('tipo.cambio.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function search(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('tipo.cambio.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('tipo.cambio.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
