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
            <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="nro_comprobante" class="d-inline">Nro.</label>
            <input type="text" id="nro_comprobante" placeholder="CX-{{ $empresa->alias }}-{{ date('y') . date('m') }}-00X" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <label for="nombre" class="d-inline">Nombre del asiento automatico</label>
            <input type="text" name="nombre" value="{{ $asiento_automatico->nombre }}" id="nombre" class="form-control font-roboto-12 disabled">
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="modulo" class="d-inline">Modulo</label>
            <input type="text" name="modulo" value="{{ $asiento_automatico->modulo->nombre }}" id="modulo" class="form-control font-roboto-12 disabled">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <div class="card card-body">
                <div class="form-group row">
                    <div class="col-md-12 pr-1 table-responsive">
                        <table id="detalle_tabla" class="table display table-bordered responsive hover-orange" style="width:100%;">
                            <thead>
                                <tr class="font-roboto-11">
                                    <td class="text-center p-1"><b>CUENTA CONTABLE</b></td>
                                    <td class="text-center p-1"><b>TIPO</b></td>
                                    <td class="text-center p-1"><b>GLOSA PARA DETALLE</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asientos_automaticos_detalles as $datos)
                                    <tr class="detalle-{{ $datos->id }} font-roboto-11">
                                        <td class="text-left p-1">
                                            {{ $datos->plan_cuenta->codigo . ' ' . $datos->plan_cuenta->nombre }}
                                        </td>
                                        <td class="text-center p-1">{{ $datos->tipos }}</td>
                                        <td class="text-left p-1">{{ $datos->glosa }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
