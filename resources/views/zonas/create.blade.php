<!DOCTYPE html>
@extends('layouts.dashboard')
<style>
    .select2 + .select2-container .select2-selection__rendered {
        font-size: 11px;
    }
    .select2-results__option {
        font-size: 13px;
    }
    .obligatorio {
        border: 1px solid red !important;
    }
    .font-weight-bold {
        font-weight: bold;
    }
    .select2-container--obligatorio .select2-selection {
        border-color: red;
    }
    #tablaContainer {
        display: flex;
        justify-content: center;
        align-items: center; 
    }

    #table-zona tbody td:hover{ 
	    background: #ffd54f !important;
	}

    table {
        border-collapse: collapse;
    }

    td {
        padding: 10px;
        width: 70px;
        height: 70px;
        text-align: center;
        vertical-align: middle;
    }

    tr:nth-child(even) td:nth-child(even),
    tr:nth-child(odd) td:nth-child(odd) {
        background-color: #f2f2f2;
    }
</style>
@section('content')
    @include('zonas.partials.form-create')
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

            if($("#filas").val() !== ""){
                crearTabla();
            }

            if($("#columnas").val() !== ""){
                crearTabla();
            }

            obligatorio();
        });

        function alerta(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function obligatorio(){
            if($("#nombre").val() !== ""){
                $("#nombre").removeClass('obligatorio');
            }else{
                $("#nombre").addClass('obligatorio');
            }
            if($("#codigo").val() !== ""){
                $("#codigo").removeClass('obligatorio');
            }else{
                $("#codigo").addClass('obligatorio');
            }
            if($("#filas").val() !== ""){
                $("#filas").removeClass('obligatorio');
            }else{
                $("#filas").addClass('obligatorio');
            }
            if($("#columnas").val() !== ""){
                $("#columnas").removeClass('obligatorio');
            }else{
                $("#columnas").addClass('obligatorio');
            }
        }

        function valideNumberSinDecimal(evt) {
            var code = (evt.which) ? evt.which : evt.keyCode;
            if ((code >= 48 && code <= 57) || code === 8) {
                return true;
            } else {
                return false;
            }
        }

        $('#filas').on('input', function () {
            crearTabla();
        });

        $('#columnas').on('input', function () {
            crearTabla();
        });

        function crearTabla(){
            eliminarTabla();
            var filas = isNaN($("#filas").val()) ? 0 : parseFloat($("#filas").val());
            var columnas = isNaN($("#columnas").val()) ? 0 : parseFloat($("#columnas").val());
            if(filas > 20 || columnas > 20){
                eliminarTabla();
                document.getElementById("filas").value = '';
                document.getElementById("columnas").value = '';
            }else{
                var tabla = document.createElement('table');
                tabla.id = 'table-zona';
                tabla.classList.add('display', 'table-bordered', 'responsive');
                var cont = 1;
                for (var i = 0; i < filas; i++) {
                    var fila = tabla.insertRow();
                    for (var j = 0; j < columnas; j++) {
                        var celda = fila.insertCell();
                        //celda.textContent = cont++;
                        celda.innerHTML = "<i class='fas fa-utensils fa-lg'></i>";
                    }
                }
                var contenedor = document.getElementById('tablaContainer');
                contenedor.appendChild(tabla);
            }
        }

        function eliminarTabla(){
            var contenedor = document.getElementById('tablaContainer');
            var tabla = contenedor.getElementsByTagName('table')[0];
            if (tabla) {
                contenedor.removeChild(tabla);
            }
        }

        function procesar() {
            if(!validar()){
                return false;
            }
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function validar(){
            if($("#nombre").val() == ""){
                alerta("<center>El campo <b>[ZONA]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#codigo").val() == ""){
                alerta("<center>El campo <b>[CELULAR]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#filas").val() == ""){
                alerta("<center>El campo <b>[ALTO]</b> es un dato obligatorio...</center>");
                return false;
            }
            if($("#columnas").val() == ""){
                alerta("<center>El campo <b>[ANCHO]</b> es un dato obligatorio...</center>");
                return false;
            }
            return true;
        }

        function confirmar(){
            var url = "{{ route('zonas.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#sucursal_id").val();
            var url = "{{ route('zonas.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop