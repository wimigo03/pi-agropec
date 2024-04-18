<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('caja_venta.partials.form-show')
    <div class="form-group row">
        <div class="col-md-6 px-0 pr-1">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="cancelar();">
                &nbsp;<i class="fas fa-arrow-left fa-fw"></i>&nbsp;Ir a listado
            </button>
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
        <div class="col-md-6 text-right px-0 pl-1">
            @if ($caja_venta->estado == '1')
                @can('caja.venta.aprobar')
                    <button class="btn btn-outline-success font-verdana" type="button" onclick="procesar();">
                        <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Aprobar
                    </button>
                @endcan
                @can('caja.venta.aprobar')
                    <button class="btn btn-outline-warning font-verdana" type="button" onclick="rechazar();">
                        <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Rechazar
                    </button>
                @endcan
            @endif
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
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
        });

        function Modal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        $('.intro').on('keypress', function(event) {
            if (event.which === 13) {
                procesar();
                event.preventDefault();
            }
        });

        function procesar() {
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function confirmar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#caja_venta_id").val();
            var url = "{{ route('caja.venta.aprobar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function rechazar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#caja_venta_id").val();
            var url = "{{ route('caja.venta.rechazar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function cancelar(){
            var id = $("#empresa_id").val();
            var url = "{{ route('caja.venta.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
