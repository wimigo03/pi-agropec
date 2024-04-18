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
    <input type="hidden" value="{{ $empresa->id }}" id="empresa_input_id">
    <form action="#" method="post" id="form" enctype="multipart/form-data">
        @csrf
        @include('personal.partials.form-editar')
    </form>
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
            $('.nav-link.active').addClass('font-weight-bold');
            $('.nav-link').on('shown.bs.tab', function (e) {
                $('.nav-link').removeClass('font-weight-bold');
                $(e.target).addClass('font-weight-bold');
            });

            if($("#tipo_familiar_input >option:selected").val() == "OTRO"){
                $("#form_otro_tipo").show();
            }else{
                $("#form_otro_tipo").hide();
            }

            if($("#licencia_conducir >option:selected").val() == "SI"){
                $("#form_licencia_categoria").show();
            }else{
                $("#form_licencia_categoria").hide();
            }

            $('#myTabs a').on('click', function (e) {
                e.preventDefault()
                $(this).tab('show')
            })

            $('.select2').select2({
                theme: "bootstrap4",
                placeholder: "--Seleccionar--",
                width: '100%'
            });

            var fecha_nacimiento = $("#fecha_nac").val();
            var calcular_edad = calcularEdad(fecha_nacimiento);
            document.getElementById("edad_nac").value = calcular_edad;

            $("#fecha_nac").datepicker({
                inline: false,
                dateFormat: "dd/mm/yy",
                autoClose: true,
                onSelect: function() {
                    var date = document.getElementById("fecha_nac").value;
                    if(validarFecha(date)){
                        var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                        if ((date.match(RegExPattern)) && (date!='')) {
                            var edad = calcularEdad(date);
                            document.getElementById("edad_nac").value = edad;
                        }
                    }
                }
            });

            if($("#horario_id >option:selected").val() != ''){
                var horario_id = $("#horario_id >option:selected").val();
                if(horario_id === '1'){
                    $("#tabla-datos-manual").hide();
                    $("#tabla-datos-oficina").show();
                    obtenerHorario(horario_id);
                }else{
                    if(horario_id === '_MANUAL_'){
                        $("#tabla-datos-oficina").hide();
                        $("#tabla-datos-manual").show();
                    }
                }
            }else{
                $("#tabla-datos-oficina").hide();
                $("#tabla-datos-manual").hide();
            }

            verificarObligatorio();
        });

        function countChars(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha_nac").value;
                if(validarFecha(date)){
                    var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
                    if ((date.match(RegExPattern)) && (date!='')) {
                        var edad = calcularEdad(date);
                        document.getElementById("edad_nac").value = edad;
                    }
                }else{
                    alertaModal("El formato de la <b>[Fecha Nacimiento]</b> no es el correcto.");
                    document.getElementById('fecha_nac').value = '';
                }
            }
        }

        function countCharsFechaIngresoFiscal(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha_ingreso_fiscal").value;
                if(!validarFecha(date)){
                    alertaModal("El formato de la <b>[Fecha Ingreso Fiscal]</b> no es el correcto.");
                    document.getElementById('fecha_ingreso_fiscal').value = '';
                }
            }
        }

        function countCharsFechaIngresoInterna(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha_ingreso_interna").value;
                if(!validarFecha(date)){
                    alertaModal("El formato de la <b>[Fecha Ingreso Interna]</b> no es el correcto.");
                    document.getElementById('fecha_ingreso_interna').value = '';
                }
            }
        }

        function countCharsFechaIngresoServicio(obj){
            var cont = obj.value.length;
            if(cont > 9){
                var date = document.getElementById("fecha_ingreso_servicio").value;
                if(!validarFecha(date)){
                    alertaModal("El formato de la <b>[Fecha Ingreso Servicio]</b> no es el correcto.");
                    document.getElementById('fecha_ingreso_servicio').value = '';
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

        function calcularEdad(fechaNacimiento) {
            var fx = fechaNacimiento.substring(6,10) + "-" + fechaNacimiento.substring(3,5) + "-" + fechaNacimiento.substring(0,2);
            var fechaNace = new Date(fx);
            var fechaActual = new Date();
            var mes = fechaActual.getMonth();
            var dia = fechaActual.getDay();
            var ano = fechaActual.getFullYear();
            fechaActual.setDate(dia);
            fechaActual.setMonth(mes);
            fechaActual.setFullYear(ano);
            return edad = Math.floor(((fechaActual - fechaNace) / (1000 * 60 * 60 * 24) / 365));
        }

        $('#tipo_familiar_input').on('change', function() {
            if($("#tipo_familiar_input >option:selected").val() == "OTRO"){
                $("#form_otro_tipo").show();
            }else{
                $("#form_otro_tipo").hide();
            }
        });

        $('#licencia_conducir').on('change', function() {
            if($("#licencia_conducir >option:selected").val() == "SI"){
                $("#form_licencia_categoria").show();
            }else{
                $("#form_licencia_categoria").hide();
            }
        });

        $('#horario_id').on('change', function() {
            var horario_id = $(this).val();
            if(horario_id === '1'){
                $("#tabla-datos-manual").hide();
                $("#tabla-datos-oficina").show();
                obtenerHorario(horario_id);
            }else{
                if(horario_id === '_MANUAL_'){
                    $("#tabla-datos-oficina").hide();
                    $("#tabla-datos-manual").show();
                }
            }
        });

        function obtenerHorario(horario_id){
            $.ajax({
                url: '/horarios/detalle/' + horario_id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#tabla-datos-oficina tbody').empty();
                    $.each(data, function(index, item) {
                        var dia = item.dia !== null && item.dia !== '' ? item.dia : '#';
                        var entrada_1 = item.entrada_1 !== null && item.entrada_1 !== '' ? item.entrada_1 : '';
                        var salida_1 = item.salida_1 !== null && item.salida_1 !== '' ? item.salida_1 : '';
                        var entrada_2 = item.entrada_2 !== null && item.entrada_2 !== '' ? item.entrada_2 : '';
                        var salida_2 = item.salida_2 !== null && item.salida_2 !== '' ? item.salida_2 : '';
                        $('#tabla-datos-oficina tbody').append(
                            '<tr class="font-verdana-bg">' +
                                '<td class="text-left p-1">' + dia + '</td>' +
                                '<td class="text-center p-1">' + entrada_1 + '</td>' +
                                '<td class="text-center p-1">' + salida_1 + '</td>' +
                                '<td class="text-center p-1">' + entrada_2 + '</td>' +
                                '<td class="text-center p-1">' + salida_2 + '</td>' +
                            '</tr>');
                    });
                },
                error: function(error) {
                    console.error('Error al llamar a la ruta:', error);
                }
            });
        }

        function alertaModal(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function agregarFamiliar(){
            if(!validarFamiliar()){
                return false;
            }
            cargarTablaFamiliar();
        }

        function validarFamiliar(){
            if($("#nombre_familiar_input").val() == ""){
                alertaModal("El campo <b>[Familiar]</b> es un dato obligatorio...");
                return false;
            }
            if($("#tipo_familiar_input >option:selected").val() == ""){
                alertaModal("El campo de seleccion <b>[Tipo]</b> es un dato obligatorio...");
                return false;
            }
            if($("#tipo_familiar_input >option:selected").val() == "OTRO"){
                if($("#otro_tipo_familiar").val() == ""){
                    alertaModal("El campo de seleccion <b>[Tipo Otro]</b> es un dato obligatorio cuando se selecciona OTRO...");
                    return false;
                }
            }
            if($("#edad_familiar_input").val() == ""){
                alertaModal("El campo <b>[Edad]</b> es un dato obligatorio...");
                return false;
            }
            if($("#telefono_familiar_input").val() == ""){
                alertaModal("El campo <b>[Telefono]</b> es un dato obligatorio...");
                return false;
            }
            if($("#ocupacion_familiar_input >option:selected").val() == ""){
                alertaModal("El campo de seleccion <b>[Ocupacion]</b> es un dato obligatorio...");
                return false;
            }
            if($("#nivel_estudio_familiar_input >option:selected").val() == ""){
                alertaModal("El campo de seleccion <b>[Nivel de Estudio]</b> es un dato obligatorio...");
                return false;
            }
            return true;
        }

        function cargarTablaFamiliar(){
            var nombre_familiar = $("#nombre_familiar_input").val();
            var tipo_familiar = $("#tipo_familiar_input >option:selected").val();
            var otro_tipo_familiar = $("#otro_tipo_familiar_input").val();
            var edad_familiar = $("#edad_familiar_input").val();
            var telefono_familiar = $("#telefono_familiar_input").val();
            var ocupacion_familiar = $("#ocupacion_familiar_input >option:selected").val();
            var nivel_estudio_familiar = $("#nivel_estudio_familiar_input >option:selected").val();
            var fila = "<tr class='font-verdana'>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='nombre_familiar[]' value='" + nombre_familiar + "'>" + nombre_familiar +
                            "</td>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='tipo_familiar[]' value='" + tipo_familiar + "'>" + tipo_familiar +
                                "<input type='hidden' name='otro_tipo_familiar[]' value='" + otro_tipo_familiar + "'>" + ' ' + otro_tipo_familiar +
                            "</td>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='edad_familiar[]' value='" + edad_familiar + "'>" + edad_familiar +
                            "</td>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='telefono_familiar[]' value='" + telefono_familiar + "'>" + telefono_familiar +
                            "</td>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='ocupacion_familiar[]' value='" + ocupacion_familiar + "'>" + ocupacion_familiar +
                            "</td>"+
                            "<td class='text-left p-1'>"+
                                "<input type='hidden' name='nivel_estudio_familiar[]' value='" + nivel_estudio_familiar + "'>" + nivel_estudio_familiar +
                            "</td>"+
                            "<td class='text-center p-1'>"+
                                "<span class='badge-with-padding badge badge-danger' onclick='eliminarItem(this);'>" +
                                      "<i class='fas fa-trash fa-fw'></i>" +
                                 "</span>" +
                            "</td>"
                        "</tr>";

            $("#detalle_tabla").append(fila);
            document.getElementById('nombre_familiar_input').value = '';
            $('#tipo_familiar_input').val('').trigger('change');
            document.getElementById('edad_familiar_input').value = '';
            document.getElementById('telefono_familiar_input').value = '';
            document.getElementById('otro_tipo_familiar_input').value = '';
            $('#ocupacion_familiar_input').val('').trigger('change');
            $('#nivel_estudio_familiar_input').val('').trigger('change');
        }

        function eliminarItem(thiss){
            var tr = $(thiss).parents("tr:eq(0)");
            tr.remove();
        }

        function valideNumberSinDecimal(evt) {
            var code = (evt.which) ? evt.which : evt.keyCode;
            if ((code >= 48 && code <= 57) || code === 8) {
                return true;
            } else {
                return false;
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

        function procesar() {
            if(!validarDatosPersonales()){
                return false;
            }
            if(!validarDatosLaboralesHeader()){
                return false;
            }
            if(!validarDatosLaboralesFiscal()){
                return false;
            }
            if(!validarDatosLaboralesInterno()){
                return false;
            }
            if(!validarDatosLaboralesServicio()){
                return false;
            }
            if(!validarDatosLaborales()){
                return false;
            }
            if(!validarDatosHorarioLaboral()){
                return false;
            }
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function validarDatosPersonales(){
            if($("#primer_nombre").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Primer Nombre]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#ap_paterno").val() == "" && $("#ap_materno").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Apellido Paterno o Apellido Materno]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#nacionalidad >option:selected").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Nacionalidad]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#ci_run").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Ci/Run]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#extension >option:selected").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Extension]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#fecha_nac").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Fecha Nacimiento]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#sexo >option:selected").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Sexo]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#licencia_conducir >option:selected").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Licencia de Conducir]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#celular").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Celular]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#domicilio").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Domicilio]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#estado_civil >option:selected").val() == ""){
                alertaModal("<center>El campo en <u>Datos Personales</u><br><b>[Estado Civil]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            return true;
        }

        function validarDatosLaboralesHeader(){
            if($("#profesion_ocupacion").val() == ""){
                alertaModal("<center>El campo en <u>Datos Laborales</u><br><b>[Profesion/Ocupacion]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#empresa_id >option:selected").val() == ""){
                alertaModal("<center>El campo en <u>Datos Laborales</u><br><b>[Empresa]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            if($("#tipo_contrato >option:selected").val() == ""){
                alertaModal("<center>El campo en <u>Datos Laborales</u><br><b>[Tipo Contrato]</b><br>es un dato obligatorio...</center>");
                return false;
            }
            return true;
        }

        function validarDatosLaboralesFiscal(){
            if($('#checkboxOne').prop('checked')) {
                if($("#fecha_ingreso_fiscal").val() == ""){
                    alertaModal("Usted tiene seleccionado <b>[DATOS LABORALES - CONTRATO FISCAL]</b><br>El campo <b>[Fecha Ingreso]</b> es obligatorio...");
                    return false;
                }
                if($("#haber_basico_fiscal").val() == ""){
                    alertaModal("Usted tiene seleccionado <b>[DATOS LABORALES - CONTRATO FISCAL]</b><br>El campo <b>[Haber Basico]</b> es obligatorio...");
                    return false;
                }
                if($("#afp_id >option:selected").val() == ""){
                    alertaModal("Usted tiene seleccionado <b>[DATOS LABORALES - CONTRATO FISCAL]</b><br>El campo <b>[Tipo AFP]</b> es obligatorio...");
                    return false;
                }
            }
            return true;
        }

        function validarDatosLaboralesInterno(){
            if($('#checkboxTwo').prop('checked')) {
                if($("#fecha_ingreso_interno").val() == ""){
                    alertaModal("Usted tiene seleccionado <b>[DATOS LABORALES - CONTRATO INTERNO]</b><br>El campo <b>[Fecha Ingreso]</b> es obligatorio...");
                    return false;
                }
                if($("#haber_basico_interno").val() == ""){
                    alertaModal("Usted tiene seleccionado <b>[DATOS LABORALES - CONTRATO INTERNO]</b><br>El campo <b>[Haber Basico]</b> es obligatorio...");
                    return false;
                }
            }
            return true;
        }

        function validarDatosLaboralesServicio(){
            if($('#checkboxTree').prop('checked')) {
                if($("#fecha_ingreso_servicio").val() == ""){
                    alertaModal("Usted tiene seleccionado <b>[DATOS LABORALES - CONTRATO SERVICIO]</b><br>El campo <b>[Fecha Ingreso]</b> es obligatorio...");
                    return false;
                }
                if($("#haber_basico_servicio").val() == ""){
                    alertaModal("Usted tiene seleccionado <b>[DATOS LABORALES - CONTRATO SERVICIO]</b><br>El campo <b>[Haber Basico]</b> es obligatorio...");
                    return false;
                }
            }
            return true;
        }

        function validarDatosLaborales(){
            var cont = 0;
            if($('#checkboxOne').prop('checked')) {
                cont = cont + 1;
            }
            if($('#checkboxTwo').prop('checked')) {
                cont = cont + 1;
            }
            if($('#checkboxTree').prop('checked')) {
                cont = cont + 1;
            }
            if(cont === 0) {
                alertaModal("Se debe seleccionar al menos un <b>[CONTRATO]</b>...");
                return false;
            }
            return true;
        }

        function validarDatosHorarioLaboral(){
            if($("#horario_id >option:selected").val() == ""){
                alertaModal("Se debe seleccionar un <b>[HORARIO LABORAL]</b>");
                return false;
            }
            return true;
        }

        $('#haber_basico_fiscal').on('input', function () {
            verificarTotales();
        });

        $('#haber_basico_interno').on('input', function () {
            verificarTotales();
        });

        $('#haber_basico_servicio').on('input', function () {
            verificarTotales();
        });

        $('#bono_fiscal').on('input', function () {
            verificarTotales();
        });

        $('#bono_interno').on('input', function () {
            verificarTotales();
        });

        $('#bono_servicio').on('input', function () {
            verificarTotales();
        });

        function verificarTotales(){
            var haber_basico_fiscal = $("#haber_basico_fiscal").val();
            var haber_basico_interno = $("#haber_basico_interno").val();
            var haber_basico_servicio = $("#haber_basico_servicio").val();
            var bono_fiscal = $("#bono_fiscal").val();
            var bono_interno = $("#bono_interno").val();
            var bono_servicio = $("#bono_servicio").val();

            var total_haber_basico = parseFloat(haber_basico_fiscal) + parseFloat(haber_basico_interno) + parseFloat(haber_basico_servicio);
            var total_bono = parseFloat(bono_fiscal) + parseFloat(bono_interno) + parseFloat(bono_servicio);
            var total_ganado = total_haber_basico + total_bono;

            total_haber_basico = isNaN(total_haber_basico) ? 0 : total_haber_basico.toFixed(2);
            total_bono = isNaN(total_bono) ? 0 : total_bono.toFixed(2);
            total_ganado = isNaN(total_ganado) ? 0 : total_ganado.toFixed(2);

            document.getElementById("total_haber_basico").value = total_haber_basico;
            document.getElementById("total_bono").value = total_bono;
            document.getElementById("total_ganado").value = total_ganado;
        }

        function verificarObligatorio(){
            if($("#primer_nombre").val() !== ""){
                $("#primer_nombre").removeClass('obligatorio');
            }else{
                $("#primer_nombre").addClass('obligatorio');
            }

            if($("#ap_paterno").val() !== ""){
                $("#ap_paterno").removeClass('obligatorio');
            }else{
                $("#ap_paterno").addClass('obligatorio');
            }

            if($("#ap_materno").val() !== ""){
                $("#ap_materno").removeClass('obligatorio');
            }else{
                $("#ap_materno").addClass('obligatorio');
            }

            if($("#nacionalidad >option:selected").val() !== ""){
                $("#obligatorio_nacionalidad_id").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_nacionalidad_id").addClass('select2-container--obligatorio');
            }

            if($("#ci_run").val() !== ""){
                $("#ci_run").removeClass('obligatorio');
            }else{
                $("#ci_run").addClass('obligatorio');
            }

            if($("#extension >option:selected").val() !== ""){
                $("#obligatorio_extension").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_extension").addClass('select2-container--obligatorio');
            }

            if($("#fecha_nac").val() !== ""){
                $("#fecha_nac").removeClass('obligatorio');
            }else{
                $("#fecha_nac").addClass('obligatorio');
            }

            if($("#lugar_nacimiento").val() !== ""){
                $("#lugar_nacimiento").removeClass('obligatorio');
            }else{
                $("#lugar_nacimiento").addClass('obligatorio');
            }

            if($("#sexo >option:selected").val() !== ""){
                $("#obligatorio_sexo").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_sexo").addClass('select2-container--obligatorio');
            }

            if($("#licencia_conducir >option:selected").val() !== ""){
                $("#obligatorio_licencia_conducir").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_licencia_conducir").addClass('select2-container--obligatorio');
            }

            if($("#celular").val() !== ""){
                $("#celular").removeClass('obligatorio');
            }else{
                $("#celular").addClass('obligatorio');
            }

            if($("#domicilio").val() !== ""){
                $("#domicilio").removeClass('obligatorio');
            }else{
                $("#domicilio").addClass('obligatorio');
            }

            if($("#estado_civil >option:selected").val() !== ""){
                $("#obligatorio_estado_civil").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_estado_civil").addClass('select2-container--obligatorio');
            }

            if($("#empresa_id >option:selected").val() !== ""){
                $("#obligatorio_empresa_id").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_empresa_id").addClass('select2-container--obligatorio');
            }

            if($("#cargo_id >option:selected").val() !== ""){
                $("#obligatorio_cargo_id").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_cargo_id").addClass('select2-container--obligatorio');
            }

            if($("#profesion_ocupacion").val() !== ""){
                $("#profesion_ocupacion").removeClass('obligatorio');
            }else{
                $("#profesion_ocupacion").addClass('obligatorio');
            }

            if($("#tipo_contrato >option:selected").val() !== ""){
                $("#obligatorio_tipo_contrato").removeClass('select2-container--obligatorio');
            }else{
                $("#obligatorio_tipo_contrato").addClass('select2-container--obligatorio');
            }

            if($('#checkboxOne').prop('checked')) {
                if($("#fecha_ingreso_fiscal").val() !== ""){
                    $("#fecha_ingreso_fiscal").removeClass('obligatorio');
                }else{
                    $("#fecha_ingreso_fiscal").addClass('obligatorio');
                }

                if($("#haber_basico_fiscal").val() !== ""){
                    if($("#haber_basico_fiscal").val() !== "0"){
                        $("#haber_basico_fiscal").removeClass('obligatorio');
                    }
                }else{
                    $("#haber_basico_fiscal").addClass('obligatorio');
                }

                if($("#afp_id >option:selected").val() !== ""){
                    $("#obligatorio_afp_id").removeClass('select2-container--obligatorio');
                }else{
                    $("#obligatorio_afp_id").addClass('select2-container--obligatorio');
                }
            }

            if($('#checkboxTwo').prop('checked')) {
                if($("#fecha_ingreso_interna").val() !== ""){
                    $("#fecha_ingreso_interna").removeClass('obligatorio');
                }else{
                    $("#fecha_ingreso_interna").addClass('obligatorio');
                }

                if($("#haber_basico_servicio").val() !== ""){
                    if($("#haber_basico_interno").val() !== "0"){
                        $("#haber_basico_interno").removeClass('obligatorio');
                    }
                }else{
                    $("#haber_basico_interno").addClass('obligatorio');
                }
            }

            if($('#checkboxTree').prop('checked')) {
                if($("#fecha_ingreso_servicio").val() !== ""){
                    $("#fecha_ingreso_servicio").removeClass('obligatorio');
                }else{
                    $("#fecha_ingreso_servicio").addClass('obligatorio');
                }

                if($("#haber_basico_servicio").val() !== ""){
                    if($("#haber_basico_servicio").val() !== "0"){
                        $("#haber_basico_servicio").removeClass('obligatorio');
                    }
                }else{
                    $("#haber_basico_servicio").addClass('obligatorio');
                }
            }
        }

        function confirmar(){
            var url = "{{ route('personal.store') }}";
            $("#form").attr('action', url);
            $(".btn").hide();
            $(".spinner-btn").show();
            $("#form").submit();
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_input_id").val();
            var url = "{{ route('personal.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
