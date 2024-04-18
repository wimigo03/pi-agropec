<!DOCTYPE html>
@extends('layouts.dashboard')
<style>
    .select2 + .select2-container .select2-selection__rendered {
        font-size: 12px;
    }
    .select2-results__option {
        font-size: 12px;
    }
</style>
@section('content')
    @include('personal.partials.search')
    <div class="form-group row">
        <div class="col-md-6 px-0 pr-1">
            @can('personal.create')
                <button class="btn btn-outline-success font-verdana" type="button" onclick="create();">
                    &nbsp;<i class="fas fa-plus"></i>&nbsp;
                </button>
                <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
            @endcan
        </div>
        <div class="col-md-6 px-0 pl-1 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="search();">
                &nbsp;<i class="fas fa-search"></i>&nbsp;Buscar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
                &nbsp;<i class="fas fa-eraser"></i>&nbsp;Limpiar
            </button>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
    @include('personal.partials.table')
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

            $('#cargo_id').select2({
                theme: "bootstrap4",
                placeholder: "--Cargo--",
                width: '100%'
            });

            $('#file_contrato').select2({
                theme: "bootstrap4",
                placeholder: "--Contratos--",
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

        function create(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val()
            var url = "{{ route('personal.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function search(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('personal.search',':id') }}";
            $("#form").attr('action', url);
            url = url.replace(':id',id);
            window.location.href = url;
            $("#form").submit();
        }

        function limpiar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('personal.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function redireccionar(id) {
            var input = document.getElementById(id);
            if(input.value == "Editar"){
                var url = '{{ route("personal.editar", ":personal") }}';
                url = url.replace(':personal', id);
                input.value = "Seleccionar";
                window.location.href = url;
            }else if(input.value == "Ver"){
                var url = '{{ route("personal.show", ":personal") }}';
                url = url.replace(':personal', id);
                input.value = "Seleccionar";
                window.location.href = url;
            }else if(input.value == "Retirar Fiscal"){
                input.value = "Seleccionar";
                var tipo = "F";
                var url = '{{ route("personal.retirar", [":personal",":tipo"]) }}';
                url = url.replace(':personal', id);
                url = url.replace(':tipo', tipo);
                window.location.href = url;
            }else if(input.value == "Retirar Interno"){
                input.value = "Seleccionar";
                var tipo = "I";
                var url = '{{ route("personal.retirar", [":personal",":tipo"]) }}';
                url = url.replace(':personal', id);
                url = url.replace(':tipo', tipo);
                window.location.href = url;
            }else if(input.value == "Retirar Servicio"){
                input.value = "Seleccionar";
                var tipo = "S";
                var url = '{{ route("personal.retirar", [":personal",":tipo"]) }}';
                url = url.replace(':personal', id);
                url = url.replace(':tipo', tipo);
                window.location.href = url;
            }else if(input.value == "File Contrato"){
                var url = '{{ route("personal.file.contrato", ":personal") }}';
                url = url.replace(':personal', id);
                input.value = "Seleccionar";
                window.location.href = url;
            }
        }
    </script>
@stop