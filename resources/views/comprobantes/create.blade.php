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
</style>
@section('content')
    @include('comprobantes.partials.form-create')
    <div class="form-group row">
        <div class="col-md-12 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                <i class="fas fa-paper-plane"></i>&nbsp;Procesar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
                &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
            </button>
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {
            $('.input-formatear-numero').each(function() {
                var formattedValue = Number($(this).val()).toLocaleString('es-ES');
                $(this).val(formattedValue);
                new Cleave(this, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });

            if($("#tipo >option:selected").val() === "1"){
                $("#hemos_entregado").hide();
                $("#entregado_recibido").show();
                $("#hemos_recibido").show();
            }else{
                if($("#tipo >option:selected").val() === "2"){
                    $("#hemos_entregado").show();
                    $("#hemos_recibido").hide();
                    $("#entregado_recibido").show();
                }else{
                    if($("#tipo >option:selected").val() === "3"){
                        $("#hemos_entregado").hide();
                        $("#hemos_recibido").hide();
                        $("#entregado_recibido").hide();
                    }else{
                        $("#hemos_entregado").hide();
                        $("#hemos_recibido").hide();
                        $("#entregado_recibido").hide();
                    }
                }
            }

            $('.select2').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%'
            });

            $("#fecha").datepicker({
                inline: false,
                dateFormat: "dd/mm/yy",
                autoClose: true,
            });

            document.getElementById("tfoot").style.display = "none";

            if($("#tipo >option:selected").val() === "1"){
                $("#hemos_entregado").hide();
                $("#entregado_recibido").show();
                $("#hemos_recibido").show();
            }

            $(".tiene-auxiliar").show();
            $(".no-tiene-auxiliar").hide();
            if($("#plan_cuenta_id >option:selected").val() != ""){
                var plan_cuenta_id = $("#plan_cuenta_id >option:selected").val();
                tiene_auxiliar(plan_cuenta_id);
            }

            obligatorio();
        });

        $('#tipo').on('change', function() {
            if($("#tipo >option:selected").val() === "1"){
                $("#hemos_entregado").hide();
                $("#entregado_recibido").show();
                $("#hemos_recibido").show();
            }else{
                if($("#tipo >option:selected").val() === "2"){
                    $("#hemos_entregado").show();
                    $("#entregado_recibido").show();
                    $("#hemos_recibido").hide();
                }else{
                    if($("#tipo >option:selected").val() === "3"){
                        $("#hemos_entregado").hide();
                        $("#hemos_recibido").hide();
                        $("#entregado_recibido").hide();
                    }else{
                        $("#hemos_entregado").hide();
                        $("#hemos_recibido").hide();
                        $("#entregado_recibido").hide();
                    }
                }
            }
        });

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

        function countChars(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha").value;
                if(!validarFecha(date)){
                    document.getElementById('fecha').value = '';
                }
            }
        }

        function validarFecha(date) {
            var regexFecha = /^\d{2}\/\d{2}\/\d{4}$/;
            if (regexFecha.test(date)) {
                var partesFecha = date.split('/');
                var dia = parseInt(partesFecha[0], 10);
                var mes = parseInt(partesFecha[1], 10);
                var anio = parseInt(partesFecha[2], 10);
                if (dia >= 1 && dia <= 31 && mes >= 1 && mes <= 12 && anio >= 1900) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function obligatorio(){
            if($("#moneda_id >option:selected").val() !== ""){
                $("#obligatorio_moneda_id").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_moneda_id").addClass('select2-container--obligatorio');
            }

            if($("#fecha").val() !== ""){
                $("#fecha").removeClass('obligatorio');
            }else{
                $("#fecha").addClass('obligatorio');
            }

            if($("#tipo >option:selected").val() !== ""){
                $("#obligatorio_tipo").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_tipo").addClass('select2-container--obligatorio');
            }

            if($("#entregado_recibido").val() !== ""){
                $("#entregado_recibido").removeClass('obligatorio');
            }else{
                $("#entregado_recibido").addClass('obligatorio');
            }

            if($("#concepto").val() !== ""){
                $("#concepto").removeClass('obligatorio');
            }else{
                $("#concepto").addClass('obligatorio');
            }
        }

        function valideNumberConDecimal(evt) {
            var code = (evt.which) ? evt.which : evt.keyCode;
            if ((code >= 48 && code <= 57) || code === 46 || code === 8) {
                if (code === 46 && evt.target.value.indexOf('.') !== -1) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        }

        function formatNumberWithThousandsSeparator(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function sumar_debe(){
            var table = document.getElementById("tabla_comprobante_detalle");
            var suma_debe = 0;
            for (var i = 1; i < table.rows.length - 1; i++) {
                var debe_cleave = table.rows[i].cells[5].textContent;
                var debe = parseFloat(debe_cleave.replace(/,/g, ''));
                if (!isNaN(debe)) {
                    suma_debe += debe;
                }
            }

            document.getElementById("total_debe").textContent = formatNumberWithThousandsSeparator(suma_debe);
            document.getElementById('monto_total').value = suma_debe.toFixed(2);
            return suma_debe.toFixed(2);
        }

        function sumar_haber(){
            var table = document.getElementById("tabla_comprobante_detalle");
            var suma_haber = 0;
            for (var i = 1; i < table.rows.length - 1; i++) {
                var haber_cleave = table.rows[i].cells[6].textContent;
                var haber = parseFloat(haber_cleave.replace(/,/g, ''));
                if (!isNaN(haber)) {
                    suma_haber += haber;
                }
            }
            document.getElementById("total_haber").textContent = formatNumberWithThousandsSeparator(suma_haber);
            return suma_haber.toFixed(2);
        }

        /*function redondear_debe(){
            var input_debe = document.getElementById("debe");
            input_debe.addEventListener("input", function() {
                var valor = this.value;
                var decimales = valor.split(".");
                if (decimales.length > 1 && decimales[1].length > 2) {
                    this.value = decimales[0] + "." + decimales[1].slice(0, 2);
                }
            });
        }*/

        /*function redondear_haber(){
            var input_haber = document.getElementById("haber");
            input_haber.addEventListener("input", function() {
                var valor = this.value;
                var decimales = valor.split(".");
                if (decimales.length > 1 && decimales[1].length > 2) {
                    this.value = decimales[0] + "." + decimales[1].slice(0, 2);
                }
            });
        }*/

        $('#debe').on('input', function () {
            procesar_debe();
        });

        function procesar_debe(){
            var debe = parseFloat($("#debe").val().replace(/,/g, ''));
            var tipo_cambio = parseFloat($("#dolar_oficial").val());
            if(!isNaN(debe)){
                var debe_sus = debe / tipo_cambio;
                document.getElementById('debe_sus').value = debe_sus;
                new Cleave('#debe_sus', {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
                document.getElementById('haber').value = '';
                document.getElementById('haber_sus').value = '';
            }else{
                document.getElementById('debe_sus').value = '';
            }
        }

        $('#haber').on('input', function () {
            procesar_haber();
        });

        function procesar_haber(){
            var haber = parseFloat($("#haber").val().replace(/,/g, ''));
            var tipo_cambio = parseFloat($("#dolar_oficial").val());
            if(!isNaN(haber)){
                haber_sus = haber / tipo_cambio;
                document.getElementById('haber_sus').value = haber_sus;
                new Cleave('#haber_sus', {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
                document.getElementById('debe').value = '';
                document.getElementById('debe_sus').value = '';
            }else{
                document.getElementById('haber_sus').value = '';
            }
        }

        function copiar_concepto(){
            var concepto = $("#concepto").val();
            document.getElementById('glosa').value = concepto;
        }

        function agregar_detalle(){
            if(!validar_detalle()){
                return false;
            }
            cargar_comprobante_detalle();
        }

        function cargar_comprobante_detalle(){
            var table = document.getElementById("tabla_comprobante_detalle");
            var rowCount = table.rows.length - 1;
            var centro_contable_id = $("#centro_contable_id >option:selected").val();
            var centro_contable = $("#centro_contable_id >option:selected").text();
            var plan_cuenta_id = $("#plan_cuenta_id >option:selected").val();
            var plan_cuenta = $("#plan_cuenta_id >option:selected").text();
            var auxiliar_id = $("#auxiliar_id >option:selected").val();
            var auxiliar = $("#auxiliar_id >option:selected").text();
            var debe = $("#debe").val() != '' ? $("#debe").val() : 0;
            var haber = $("#haber").val() != '' ? $("#haber").val() : 0;
            var glosa = $("#glosa").val();
            var fila = "<tr class='font-roboto-11'>"+
                            "<td class='text-left p-1'>"+
                                    rowCount +
                            "</td>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='centro_contable_id[]' value='" + centro_contable_id + "'>" +
                                    centro_contable +
                            "</td>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='plan_cuenta_id[]' value='" + plan_cuenta_id + "'>" +
                                    plan_cuenta +
                            "</td>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='auxiliar_id[]' value='" + auxiliar_id + "'>" +
                                    auxiliar +
                            "</td>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='glosa[]' value='" + glosa + "'>" +
                                    glosa +
                            "</td>"+
                            "<td class='text-right p-1'>"+
                                "<input type='hidden' name='debe[]' value='" + debe + "'>" +
                                    debe +
                            "</td>"+
                            "<td class='text-right p-1'>"+
                                "<input type='hidden' name='haber[]' value='" + haber + "'>" +
                                    haber +
                            "</td>"+
                            "<td class='text-center p-1'>"+
                                "<span class='badge-with-padding badge badge-danger' onclick='eliminarItem(this);'>" +
                                      "<i class='fas fa-trash fa-fw'></i>" +
                                 "</span>" +
                            "</td>"
                        "</tr>";

            $("#tabla_comprobante_detalle").append(fila);
            document.getElementById("tfoot").style.display = "table-footer-group";
            $('#centro_contable_id').val('').trigger('change');
            $('#plan_cuenta_id').val('').trigger('change');
            $('#auxiliar_id').val('').trigger('change');
            document.getElementById('debe').value = '';
            document.getElementById('haber').value = '';
            document.getElementById('debe_sus').value = '';
            document.getElementById('haber_sus').value = '';
            document.getElementById('glosa').value = '';
            sumar_debe();
            sumar_haber();
        }

        function eliminarItem(thiss){
            var tr = $(thiss).parents("tr:eq(0)");
            tr.remove();
            sumar_debe();
            sumar_haber();
        }

        function validar_detalle(){
            if($("#centro_contable_id >option:selected").val() == ""){
                alert("El campo de seleccion <b>[CENTRO CONTABLE]</b> es un dato obligatorio...");
                return false;
            }

            if($("#plan_cuenta_id >option:selected").val() == ""){
                alert("El campo de seleccion <b>[CUENTA]</b> es un dato obligatorio...");
                return false;
            }

            if($("#debe").val() != ""){
                if($("#haber").val() != ""){
                    alert("El campo <b>[HABER]</b> es un dato obligatorio...");
                    return false;
                }
            }

            if($("#haber").val() != ""){
                if($("#debe").val() != ""){
                    alert("El campo <b>[DEBE]</b> es un dato obligatorio...");
                    return false;
                }
            }

            if($("#glosa").val() == ""){
                alert("El campo <b>[GLOSA]</b> es un dato obligatorio...");
                return false;
            }
            return true;
        }

        function procesar() {
            if(!validar()){
                return false;
            }

            if(!verificar_saldo()){
                return false;
            }

            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function verificar_saldo(){
            var table = document.getElementById("tabla_comprobante_detalle");
            if(table.rows.length < 3){
                alert("No existen registro para verificar. por favor ingrese los datos correspondientes...");
                return false;
            }else{
                var total_debe = sumar_debe();
                var total_haber = sumar_haber();
                if(total_debe != total_haber){
                    alert("El total <b>[DEBE - HABER]</b> no coincide...");
                    return false;
                }
                return true;
            }
        }

        function validar(){
            if($("#moneda_id >option:selected").val() == ""){
                alert("La <b>[MONEDA]</b> no fue seleccionado...");
                return false;
            }

            if($("#fecha").val() == ""){
                alert("La <b>[FECHA]</b> es un campo obligatorio...");
                return false;
            }

            if($("#tipo >option:selected").val() == ""){
                alert("La <b>[TIPO]</b> no fue seleccionado...");
                return false;
            }

            /*if($("#entregado_recibido").val() == ""){
                alert("La <b>[ENTREGADO]</b> es un campo obligatorio...");
                return false;
            }*/

            if($("#concepto").val() == ""){
                alert("La <b>[CONCEPTO]</b> es un campo obligatorio...");
                return false;
            }

            return true;
        }

        $('#plan_cuenta_id').on('change', function() {
            if($("#plan_cuenta_id >option:selected").val() != ''){
                var plan_cuenta_id = $("#plan_cuenta_id >option:selected").val();
                tiene_auxiliar(plan_cuenta_id);
            }
        });

        function tiene_auxiliar(id){
            $.ajax({
                type: 'GET',
                url: '/comprobante/tiene_auxiliar/'+id,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(json){
                    if(json.auxiliar === '1'){
                        $(".tiene-auxiliar").show();
                        $(".no-tiene-auxiliar").hide();
                    }else{
                        $(".tiene-auxiliar").hide();
                        $(".no-tiene-auxiliar").show();
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }

        function confirmar(){
            var url = "{{ route('comprobante.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('comprobante.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
