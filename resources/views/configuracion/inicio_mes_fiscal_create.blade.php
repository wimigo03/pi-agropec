<!DOCTYPE html>
@extends('layouts.dashboard')
@section('content')
    @include('configuracion.partials.FormInicioMesFiscal')
    @if (count($anteriores) > 0)
        @include('configuracion.partials.TableInicioMesFiscal')
    @endif
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $("#fecha_otro_sistema").hide();
            if($("#pregunta_1 >option:selected").val() === "1"){
                $("#fecha_otro_sistema").show();
            }else{
                $("#fecha_otro_sistema").hide();
                document.getElementById('fecha').value = '';
            }

            $('.select2').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%'
            });

            $('#mes').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%'
            });

            var cleave = new Cleave('#anho', {
                numeral: true,
                numeralThousandsGroupStyle: 'none',
                numeralDecimalMark: '',
                numeralDecimalScale: 0,
                numeralIntegerScale: 4,
                rawValueTrimPrefix: true
            });

            $("#fecha").datepicker({
                inline: false,
                dateFormat: "dd/mm/yy",
                autoClose: true,
            });

            var date = document.getElementById('fecha');
            if(date != null){
                var cleave = new Cleave(date, {
                    date: true,
                    datePattern: ['d', 'm', 'Y']
                });
            }
        });

        $('#pregunta_1').on('change', function() {
                if($("#pregunta_1 >option:selected").val() === "1"){
                    $("#fecha_otro_sistema").show();
                }else{
                    $("#fecha_otro_sistema").hide();
                    document.getElementById('fecha').value = '';
                }
            }
        );

        function alert(mensaje){
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
            if(!validar()){
                return false;
            }

            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function validar(){
            if($("#fecha").val() == ""){
                alert("[CAMPO OBLIGATORIO]");
                return false;
            }

            return true;
        }

        function confirmar(){
            var url = "{{ route('configuracion.inicio.mes.fiscal.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('configuracion.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
