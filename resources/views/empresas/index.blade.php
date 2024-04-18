<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    <div class="form-group row">
        <div class="col-md-12">
            <div class="card-header header">
                <div class="row">
                    <div class="col-md-10 font-roboto-17" style="display: flex; align-items: flex-end;">
                        <span class="btn btn-sm btn-outline-dark font-roboto-12" id="toggleSubMenu" style="cursor: pointer;">
                            <i class="fas fa-address-card fa-fw fa-beat"></i>
                        </span>&nbsp;
                        <b>{{ $cliente->nombre }} - EMPRESAS</b>
                    </div>
                    <div class="col-md-2">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('empresas.partials.search')
    <div class="form-group row">
        <div class="col-md-6 px-0 pr-1">
            <button class="btn btn-outline-success font-verdana" type="button" onclick="create();">
                <i class="fas fa-plus fa-fw"></i>
            </button>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
        <div class="col-md-6 px-0 pl-1 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar
            </button>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
    @include('empresas.partials.table')
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
        });

        function create(){
            var id = $("#cliente_id").val();
            var url = "{{ route('empresas.create',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function valideNumberInteger(evt){
            var code = (evt.which) ? evt.which : evt.keyCode;
            if(code>=48 && code<=57){
                return true;
            }else{
                return false;
            }
        }
    </script>
@stop
