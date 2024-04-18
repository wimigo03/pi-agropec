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
        border-color: red !important;
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
        width: 60px;
        height: 60px;
        text-align: center;
        vertical-align: middle;
    }

    tr:nth-child(even) td:nth-child(even),
    tr:nth-child(odd) td:nth-child(odd) {
        background-color: #f2f2f2;
    }

    
    .imagen-con-texto {
        position: relative;
        top: 20%;
        display: inline-block;
    }

    .texto-sobre-imagen {
        position: absolute;
        top: -10%;
        left: 49%;
        transform: translate(-50%, -50%);
        padding: 5px;
    }

    .circulo-texto {
        position: absolute; 
        top: -10%; 
        left: 50%; 
        transform: translate(-50%, -50%); 
        width: 30px; 
        height: 30px; 
        border-radius: 50%; 
        overflow: hidden;
	    opacity: 0.8;
        border: 1px solid #ffffff;
        background-color: #ffc107;
    }

    .hiddenContent {
        opacity: 0;
        transition: opacity .3s ease;
    }
</style>
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        @include('layouts.partials.header')
        @include('mesas.partials.form-setting')
        {{--<div class="form-group row">
            <div class="col-md-12 text-right">
                <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                    <i class="fas fa-paper-plane"></i>&nbsp;Procesar
                </button>
                <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
                    &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
                </button>
                <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
            </div>
        </div>--}}
        @include('mesas.partials.modal-asignar-mesa')
    </div>
</div>
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

            var primerZonaId = $('#myTabs .nav-link:first').data('zona-id');
            document.getElementById("zona_id").value = primerZonaId;

        });

        function alternarDatos() {
            var datosMesa = document.getElementById("datos-mesa");
            datosMesa.classList.toggle("hiddenContent");
            var mapaMesa = document.getElementById("mapa-mesa");
            if (datosMesa.style.display === "none") {
                mapaMesa.classList.remove("col-md-12");
                mapaMesa.classList.add("col-md-8");
                datosMesa.style.display = "block";
            } else {
                datosMesa.style.display = "none";
                mapaMesa.classList.remove("col-md-8");
                mapaMesa.classList.add("col-md-12");
            }
        }

        $('#myTabs a').on('shown.bs.tab', function (e) {
            var zonaId = $(e.target).data('zona-id');
            document.getElementById("zona_id").value = zonaId;
        });

        function alerta(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }
        function cargarMesas(i,j) {
            var zona_id = $("#zona_id").val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            getMesasByZona(i,j,zona_id,CSRF_TOKEN);
        }

        function getMesasByZona(i,j,zona_id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/mesas/get_mesas_by_zona',
                data: {
                    _token: CSRF_TOKEN,
                    id: zona_id
                },
                success: function(data){
                    if(data.mesas){
                        var arr = Object.values($.parseJSON(data.mesas));
                        $("#mesa_id").empty();
                        var select = $("#mesa_id");
                        document.getElementById("fila").value = i;
                        document.getElementById("columna").value = j;
                        select.append($("<option></option>").attr("value", '').text('--Mesa--'));
                        var mesaIdSeleccionado = localStorage.getItem('mesaIdSeleccionado');
                        $.each(arr, function(index, json) {
                            var opcion = $("<option></option>").attr("value", json.id).text(json.numero_sillas);
                            if (json.id == mesaIdSeleccionado) {
                                opcion.attr('selected', 'selected');
                            }
                            select.append(opcion);
                        });
                        select.on('change', function() {
                            localStorage.setItem('mesaIdSeleccionado', $(this).val());
                        });
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        function cancelarCargarMesa(){
            localStorage.clear();
        }

        function storeCargarMesa(){
            localStorage.clear();
            var url = "{{ route('mesas.store.cargar') }}";
            $("#form-asignar-mesa").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form-asignar-mesa").submit();
        }

        function procesar() {
            if(!validar()){
                return false;
            }
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function confirmar(){
            var url = "{{ route('mesas.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('mesas.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop