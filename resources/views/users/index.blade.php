<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('users.partials.search')
    <div class="form-group row">
        <div class="col-md-12 px-0 pl-1 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="search();">
                &nbsp;<i class="fas fa-search"></i>&nbsp;Buscar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
                &nbsp;<i class="fas fa-eraser"></i>&nbsp;Limpiar
            </button>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
    @include('users.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
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
            var id = $("#empresa_id").val();
            var url = "{{ route('users.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            localStorage.clear();
            var id = $("#empresa_id").val();
            var url = "{{ route('users.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        /*$('#empresa_id').change(function() {
            var id = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            getCargos(id,CSRF_TOKEN);
            getRoles(id,CSRF_TOKEN);
        });

        function getCargos(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/cargos/get_datos_cargo_by_empresa',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){
                    if(data.cargos){
                        var arr = Object.values($.parseJSON(data.cargos));
                        $("#cargo_id").empty();
                        var select = $("#cargo_id");
                        select.append($("<option></option>").attr("value", '').text('--Cargo--'));
                        var cargoIdSeleccionado = localStorage.getItem('cargoIdSeleccionado');
                        $.each(arr, function(index, json) {
                            var opcion = $("<option></option>").attr("value", json.id).text(json.nombre);
                            if (json.id == cargoIdSeleccionado) {
                                opcion.attr('selected', 'selected');
                            }
                            select.append(opcion);
                        });
                        select.on('change', function() {
                            localStorage.setItem('cargoIdSeleccionado', $(this).val());
                        });
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        function getRoles(id,CSRF_TOKEN){
            $.ajax({
                type: 'GET',
                url: '/roles/get_datos_by_empresa',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data){
                    if(data.roles){
                        var arr = Object.values($.parseJSON(data.roles));
                        $("#role_id").empty();
                        var select = $("#role_id");
                        select.append($("<option></option>").attr("value", '').text('--Role--'));
                        var roleIdSeleccionado = localStorage.getItem('roleIdSeleccionado');
                        $.each(arr, function(index, json) {
                            var opcion = $("<option></option>").attr("value", json.id).text(json.name);
                            if (json.id == roleIdSeleccionado) {
                                opcion.attr('selected', 'selected');
                            }
                            select.append(opcion);
                        });
                        select.on('change', function() {
                            localStorage.setItem('roleIdSeleccionado', $(this).val());
                        });
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }*/
    </script>
@stop
