<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-1 pl-1">
            <form action="#" method="get" id="form_estructura">
                <select name="empresa_id" id="empresa_id" class="form-control">
                    <option value="">-</option>
                    @foreach ($empresas as $index => $value)
                        <option value="{{ $index }}" @if(isset($empresa_id) ? $empresa_id : request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12 text-center">
            <img src="/images/pi-agropec.jpg" alt="pi-agropec" class="imagen-pi-resto">
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            if($("#empresa_id >option:selected").val() != ''){
                var id = $("#empresa_id >option:selected").val();
                planCuentasByEmpresa(id);
            }

            $('#empresa_id').select2({
                theme: "bootstrap4",
                placeholder: "--Empresa--",
                width: '100%'
            });
        });

        function valideNumberSinDecimal(evt) {
            var code = (evt.which) ? evt.which : evt.keyCode;
            if ((code >= 48 && code <= 57) || code === 8) {
                return true;
            } else {
                return false;
            }
        }

        $('#empresa_id').change(function() {
            var id = $(this).val();
            planCuentasByEmpresa(id);
        });

        function planCuentasByEmpresa(id){
            var status = '[]';
            var url = "{{ route('plan_cuentas.index',[':id',':status']) }}";
            url = url.replace(':id',id);
            url = url.replace(':status',status);
            window.location.href = url;
        }
    </script>
@stop
