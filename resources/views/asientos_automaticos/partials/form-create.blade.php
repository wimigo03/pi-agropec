<div class="form-group row">
    <div class="col-md-12 font-roboto-12 text-center" style="text-align: justify; border-bottom: 1px solid red;">
        <strong>Para asegurarnos de que la GLOSA se guarde con la información correcta, por favor, utiliza los siguientes marcadores de posición que luego serán reemplazados por la información correspondiente</strong>
    </div>
    <div class="col-md-6 px-0 pr-1 font-roboto-12" style="text-align: justify; border-bottom: 1px solid red;">
        <strong>{NRO_TRANSACCION}: </strong>Para insertar el número de la Transacción Origen.<br>
        <strong>{PROVEEDOR}: </strong>Para insertar el Nombre/Razón Social del proveedor si es aplicable.<br>
        <strong>{NRO_FACTURA}: </strong>Para insertar el número de factura si es aplicable.<br>
        <strong>{PROYECTO}: </strong>Para insertar el nombre del PROYECTO.<br>
    </div>
    <div class="col-md-6 px-0 pl-1 font-roboto-12" style="text-align: justify; border-bottom: 1px solid red;">
        <strong>{CENTRO_COSTO}: </strong>Para insertar el nombre del CENTRO.<br>
        <strong>{MONTO}: </strong>Para insertar el total de la factura o transacción.<br>
        <strong>{GLOSA}: </strong>Para insertar la descripción/observación de la Transacción Origen desde la cual se está generando el asiento.
    </div>
</div>
<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row">
        <div class="col-md-3 px-0 pr-1 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
            <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="nro_comprobante" class="d-inline">Nro.</label>
            <input type="text" id="nro_comprobante" placeholder="CX-{{ $empresa->alias }}-{{ date('y') . date('m') }}-00X" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <label for="nombre" class="d-inline">Nombre del asiento automatico</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="modulo" class="d-inline">Modulo</label>
            <select name="modulo_id" id="modulo_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($modulos as $index => $value)
                    <option value="{{ $index }}" @if(old('modulo_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <div class="card card-body">
                <div class="form-group row">
                    <div class="col-md-3 px-0 pr-1 font-roboto-12">
                        <label for="plan_cuenta" class="d-inline">Cuenta</label>
                        <select name="plan_cuenta_id" id="plan_cuenta_id" class="form-control select2">
                            <option value="">-</option>
                            @foreach ($plan_cuentas as $index => $value)
                                <option value="{{ $index }}" @if(old('plan_cuenta_id') == $index) selected @endif >{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
                        <label for="tipo" class="d-inline">Tipo</label>
                        <select name="tipo" id="tipo" class="form-control font-roboto-12 select2">
                            <option value="">--Seleccionar--</option>
                            @foreach ($tipos as $index => $value)
                                <option value="{{ $index }}" @if(old('tipo') == $index) selected @endif >{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-10 px-0 pr-1 font-roboto-12">
                        <label for="glosa" class="d-inline">Glosa</label>
                        <textarea name="glosa" style="height: 33px;" id="glosa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase();">{{ old('glosa') }}</textarea>
                    </div>
                    <div class="col-md-2 px-0 pl-1 text-right font-roboto-12">
                        <br>
                        <span class="tts:left tts-slideIn tts-custom" aria-label="Agregar detalle">
                            <button type="button" class="btn btn-outline-success font-roboto-12" onclick="agregar();">
                                <i class="fa fa-plus fa-fw"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 pr-1 table-responsive">
                        <table id="detalle_tabla" class="table display table-bordered responsive hover-orange" style="width:100%;">
                            <thead>
                                <tr class="font-roboto-11">
                                    <td class="text-center p-1"><b>CUENTA CONTABLE</b></td>
                                    <td class="text-center p-1"><b>TIPO</b></td>
                                    <td class="text-center p-1"><b>GLOSA PARA DETALLE</b></td>
                                    <td class="text-center p-1"><b><i class="fa-solid fa-bars"></i></b></td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
