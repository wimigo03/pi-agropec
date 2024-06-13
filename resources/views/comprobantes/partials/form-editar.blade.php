<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $comprobante->empresa_id }}" id="empresa_id">
            <input type="text" value="{{ $comprobante->empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="dolar_oficial" class="d-inline">Tipo de Cambio</label>
            <input type="text" value="{{ $comprobante->tipo_cambio }}" id="dolar_oficial" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="ufv" class="d-inline">Ufv</label>
            <input type="text" value="{{ $comprobante->ufv }}" id="ufv" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="user" class="d-inline">Usuario</label>
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <input type="text" value="{{ Auth::user()->username }}" id="username" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-2 px-1 pr-1 font-roboto-12">
            <label for="nro_comprobante" class="d-inline">Nro.</label>
            <input type="hidden" name="comprobante_id" value="{{ $comprobante->id }}">
            <input type="text" id="nro_comprobante" value="{{ $comprobante->nro_comprobante }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="moneda" class="d-inline">Moneda</label>
            <input type="text" value="{{ $comprobante->datos_moneda->nombre }}" id="moneda" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="fecha" class="d-inline">Fecha</label>
            <input type="text" name="fecha" value="{{ \Carbon\Carbon::parse($comprobante->fecha)->format('d-m-Y') }}" id="fecha" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="tipo" class="d-inline">Tipo</label>
            <input type="text" value="{{ App\Models\Comprobante::TIPOS[$comprobante->tipo] }}" id="tipo" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-3 px-1 pl-1 font-roboto-12">
            <div id="hemos_recibido">
                <label for="hemos_recibido" class="d-inline">Hemos recibido de</label>
            </div>
            <div id="hemos_entregado">
                <label for="hemos_entregado" class="d-inline">Hemos Entregado a</label>
            </div>
            <input type="text" name="entregado_recibido" value="{{ $comprobante->entregado_recibido }}" id="entregado_recibido" class="form-control font-roboto-12 obligatorio intro" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-9 px-1 pl-1 font-roboto-12">
            <label for="concepto" class="d-inline">Concepto</label>
            <input type="text" name="concepto" value="{{ $comprobante->concepto }}" id="concepto" class="form-control font-roboto-12 obligatorio intro" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 px-1 font-roboto-12 text-center">
            <b>_ DETALLE DEL COMPROBANTE _</b>
            <hr style="margin-top: 0; margin-bottom: 10;">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="centro" class="d-inline">Centro</label>
            <select id="centro_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($centros as $index => $value)
                    <option value="{{ $index }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="subcentro_id" class="d-inline">Sub Centro</label>
            <select id="sub_centro_id" class="form-control select2">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-5 px-1 pr-1 font-roboto-12">
            <label for="plan_cuenta" class="d-inline">Cuenta</label>
            <select id="plan_cuenta_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($plan_cuentas as $index => $value)
                    <option value="{{ $index }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12 tiene-auxiliar">
            <label for="auxiliar" class="d-inline">Auxiliar</label>
            <select id="auxiliar_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($plan_cuentas_auxiliares as $index => $value)
                    <option value="{{ $index }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12 no-tiene-auxiliar">
            &nbsp;
        </div>
        <div class="col-md-1 pr-1 pl-1 font-roboto-12">
            <label for="debe" class="d-inline">Debe (Bs.)</label>
            <input type="text" placeholder="0" id="debe" class="form-control font-roboto-12 input-formatear-numero">
        </div>
        <div class="col-md-1 px-1 pl-1 font-roboto-12">
            <label for="haber" class="d-inline">Haber (Bs.)</label>
            <input type="text" placeholder="0" id="haber" class="form-control font-roboto-12 input-formatear-numero">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-9 px-1 pr-1 font-roboto-12">
            <div class="row">
                <div class="col-md-6">
                    <label for="glosa" class="d-inline">Glosa</label>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-danger" style="cursor: pointer" onclick="copiar_concepto();">[Copiar desde concepto]</span>
                </div>
            </div>
            <input type="text" id="glosa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase();">
        </div>
        <div class="col-md-1 pr-1 pl-1 font-roboto-12">
            <label for="debe" class="d-inline">Debe ($u$)</label>
            <input type="text" placeholder="0" id="debe_sus" class="form-control font-roboto-12 input-formatear-numero" readonly>
        </div>
        <div class="col-md-1 pr-1 pl-1 font-roboto-12">
            <label for="haber" class="d-inline">Haber ($u$)</label>
            <input type="text" placeholder="0" id="haber_sus" class="form-control font-roboto-12 input-formatear-numero" readonly>
        </div>
        <div class="col-md-1 px-1 pl-1 font-roboto-12 text-right">
            <br>
            <span class="tts:left tts-slideIn tts-custom" aria-label="Registrar">
                <button type="button" class="btn btn-outline-success btn-sm" onclick="agregar_detalle();">
                    <i class="fa fa-fw fa-plus-circle"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12 px-1 table-responsive">
            <table id="tabla_comprobante_detalle" class="table display table-bordered responsive" style="width:100%;">
                <thead>
                    <tr class="font-roboto-11 bg-secondary text-white">
                        <td class="text-center p-1"><b>N°</b></td>
                        <td class="text-center p-1"><b>CUENTA</b></td>
                        <td class="text-center p-1"><b>CENTRO</b></td>
                        <td class="text-center p-1"><b>SUBCENTRO</b></td>
                        <td class="text-center p-1"><b>AUXILIAR</b></td>
                        <td class="text-center p-1"><b>GLOSA</b></td>
                        <td class="text-right p-1"><b>DEBE</b></td>
                        <td class="text-right p-1"><b>HABER</b></td>
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1
                    @endphp
                    @foreach ($comprobante_detalles as $datos)
                        <tr class="detalle-{{ $datos->id }} font-roboto-11">
                            <td class="text-left p-1">
                                {{ $cont++ }}
                                <input type="hidden" value="{{ $datos->id }}" class="comprobante_detalle_id">
                            </td>
                            <td class="text-left p-1">{{ $datos->plan_cuenta->nombre }}</td>
                            <td class="text-left p-1">{{ $datos->centro != null ? $datos->centro->nombre : '' }}</td>
                            <td class="text-left p-1">{{ $datos->subcentro != null ? $datos->subcentro->nombre : '' }}</td>
                            <td class="text-left p-1">{{ $datos->plan_cuenta_auxiliar_id != null ? $datos->plan_cuenta_auxiliar->nombre : '-' }}</td>
                            <td class="text-left p-1">{{ $datos->glosa }}</td>
                            <td class="text-right p-1">{{ number_format($datos->debe,2,'.',',') }}</td>
                            <td class="text-right p-1">{{ number_format($datos->haber,2,'.',',') }}</td>
                            <td class='text-center p-1'>
                                <span class='badge-with-padding badge badge-danger' onclick='eliminarItem(this,{{ $datos->id }});'>
                                      <i class='fas fa-trash fa-fw'></i>
                                 </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot id="tfoot">
                    <tr class="font-roboto-11">
                        <input type="hidden" value="#" name="monto_total" id="monto_total">
                        <td class="text-center p-1" colspan="6"><b>TOTAL</b></td>
                        <td id="total_debe" class="text-right p-1"><b>{{ number_format($total_debe,2,'.',',') }}</b></td>
                        <td id="total_haber" class="text-right p-1"><b>{{ number_format($total_haber,2,'.',',') }}</b></td>
                        <td class="text-center p-1">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1 text-right">
        <span class="btn btn-outline-primary font-roboto-12" onclick="procesar();">
            <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Actualizar
        </span>
        <span class="btn btn-outline-danger font-roboto-12" onclick="cancelar();">
            <i class="fas fa-times fa-fw"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
