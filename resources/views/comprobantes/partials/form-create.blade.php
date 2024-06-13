<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($empresas as $index => $value)
                    <option value="{{ $index }}" @if(request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="dolar_oficial" class="d-inline">Tipo de Cambio</label>
            <input type="text" name="dolar_oficial" value="{{ $tipo_cambio->dolar_oficial }}" id="dolar_oficial" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="ufv" class="d-inline">Ufv</label>
            <input type="text" name="ufv" value="{{ $tipo_cambio->ufv }}" id="ufv" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="user" class="d-inline">Usuario</label>
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <input type="text" value="{{ Auth::user()->username }}" id="username" class="form-control font-roboto-12" disabled>
        </div>
        @can('comprobantef.create')
            <div class="col-md-3 px-1 pl-1 font-roboto-12 text-center">
                <br>
                <label for="copia" class="d-inline">¿Con Copia?</label>
                <input type="checkbox" id="copia" class="ml-2" name="copia" {{ old('copia') ? 'checked' : '' }}>
            </div>
        @endcan
    </div>
    <div class="form-group row">
        <div class="col-md-2 px-1 pr-1 font-roboto-12">
            <label for="moneda" class="d-inline">Moneda</label>
            <div class="select2-container--obligatorio" id="obligatorio_moneda_id">
                <select name="moneda_id" id="moneda_id" class="form-control select2" onchange="obligatorio();">
                    <option value="">-</option>
                    @foreach ($monedas as $index => $value)
                        <option value="{{ $index }}" @if(old('moneda_id') == $index) selected @endif >{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="fecha" class="d-inline">Fecha</label>
            <input type="text" name="fecha" value="{{ old('fecha') }}" id="fecha" placeholder="dd-mm-aaaa" class="form-control font-roboto-12 obligatorio" data-language="es" oninput="obligatorio();">
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="tipo" class="d-inline">Tipo</label>
            <div class="select2-container--obligatorio" id="obligatorio_tipo">
                <select name="tipo" id="tipo" class="form-control font-roboto-12 select2" onchange="obligatorio();">
                    <option value="">--Seleccionar--</option>
                    @foreach ($tipos as $index => $value)
                        <option value="{{ $index }}" @if(old('tipo') == $index) selected @endif >{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 px-1 pl-1 font-roboto-12">
            <div id="hemos_recibido">
                <label for="hemos_recibido" class="d-inline">Hemos recibido de</label>
            </div>
            <div id="hemos_entregado">
                <label for="hemos_entregado" class="d-inline">Hemos Entregado a</label>
            </div>
            <input type="text" name="entregado_recibido" value="{{ old('entregado_recibido') }}" id="entregado_recibido" class="form-control font-roboto-12 obligatorio intro" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-9 px-1 pr-1 font-roboto-12">
            <label for="concepto" class="d-inline">Concepto</label>
            <input type="text" name="concepto" value="{{ old('concepto') }}" id="concepto" class="form-control font-roboto-12 obligatorio intro" oninput="this.value = this.value.toUpperCase(); obligatorio();">
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
            </select>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12 tiene-auxiliar">
            <label for="auxiliar" class="d-inline">Auxiliar</label>
            <select id="auxiliar_id" class="form-control select2">
            </select>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12 no-tiene-auxiliar">
            &nbsp;
        </div>
        <div class="col-md-1 pr-1 pl-1 font-roboto-12">
            <label for="debe" class="d-inline">Debe (Bs.)</label>
            <input type="text" placeholder="0" id="debe" class="form-control font-roboto-12 input-formatear-numero">
        </div>
        <div class="col-md-1 pr-1 pl-1 font-roboto-12">
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
                        <td class="text-center p-1"><b>DEBE</b></td>
                        <td class="text-center p-1"><b>HABER</b></td>
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot id="tfoot">
                    <tr class="font-roboto-11">
                        <input type="hidden" value="#" name="monto_total" id="monto_total">
                        <td class="text-center p-1" colspan="6"><b>TOTAL</b></td>
                        <td id="total_debe" class="text-right p-1"><b></b></td>
                        <td id="total_haber" class="text-right p-1"><b></b></td>
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
            <i class="fas fa-paper-plane"></i>&nbsp;Procesar
        </span>
        <span class="btn btn-outline-danger font-roboto-12" onclick="cancelar();">
            &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
