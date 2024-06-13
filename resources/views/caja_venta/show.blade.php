<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('caja_venta.partials.form-show')
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
