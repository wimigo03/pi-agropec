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
                        <b>CLIENTES</b>
                    </div>
                    <div class="col-md-2">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('clientes.partials.table')
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {

        });

        function create(){
            $(".btn").hide();
            $(".spinner-btn").show();
            window.location.href = "{{ route('clientes.create') }}";
        }
    </script>
@stop
