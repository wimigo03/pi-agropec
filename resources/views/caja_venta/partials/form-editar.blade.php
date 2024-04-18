<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
    <input type="hidden" name="asiento_automatico_id" value="{{ $asiento_automatico->id }}">
    <div class="form-group row">
        <div class="col-md-3 px-0 pr-1 font-roboto-12">
            <label for="nombre" class="d-inline">Nombre del asiento automatico</label>
            <input type="text" name="nombre" value="{{ $asiento_automatico->nombre }}" id="nombre" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-0 pr-1 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="nro_comprobante" class="d-inline">Nro.</label>
            <input type="text" id="nro_comprobante" placeholder="CX-{{ $empresa->alias }}-{{ date('y') . date('m') }}-00X" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="moneda" class="d-inline">Moneda</label>
            <select name="moneda_id" id="moneda_id" class="form-control select2">
                @foreach ($monedas as $moneda)
                    <option value="{{ $moneda->id }}"
                        @if($moneda->id == old('moneda_id') || (isset($asiento_automatico) && $asiento_automatico->moneda_id == $moneda->id))
                            selected
                        @endif>
                        {{ $moneda->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="modulo" class="d-inline">Modulo</label>
            <select name="modulo_id" id="modulo_id" class="form-control select2">
                @foreach ($modulos as $modulo)
                    <option value="{{ $modulo->id }}"
                        @if($modulo->id == old('modulo_id') || (isset($asiento_automatico) && $asiento_automatico->modulo_id == $modulo->id))
                            selected
                        @endif>
                        {{ $modulo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-2 px-0 pr-1 font-roboto-12">
            <label for="tipo" class="d-inline">Tipo</label>
            <select name="tipo" id="tipo" class="form-control font-roboto-12 select2">
                @foreach ($tipos as $index => $value)
                    <option value="{{ $index }}"
                    @if($asiento_automatico->tipo == old('tipo') || (isset($asiento_automatico) && $asiento_automatico->tipo == $index))
                        selected
                    @endif >
                    {{ $value }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-9 pr-1 pl-1 font-roboto-12">
            <label for="concepto" class="d-inline">Concepto</label>
            <input type="text" name="concepto" value="{{ $asiento_automatico->concepto }}" id="concepto" class="form-control font-roboto-12 obligatorio intro" oninput="this.value = this.value.toUpperCase();">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-0 pr-1 font-roboto-12">
            <label for="plan_cuenta" class="d-inline">Cuenta</label>
            <select name="plan_cuenta_id" id="plan_cuenta_id" class="form-control select2">
                @foreach ($plan_cuentas as $plan_cuenta)
                    <option value="{{ $plan_cuenta->id }}"
                        @if($plan_cuenta->id == old('plan_cuenta_id') || (isset($asiento_automatico) && $asiento_automatico->plan_cuenta_id == $plan_cuenta->id))
                            selected
                        @endif>
                        {{ $plan_cuenta->cuenta_contable }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-9 px-0 pr-1 font-roboto-12">
            <div class="row">
                <div class="col-md-6">
                    <label for="glosa" class="d-inline">Glosa</label>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-danger" style="cursor: pointer" onclick="copiar_concepto();">[Copiar desde concepto]</span>
                </div>
            </div>
            <input type="text" name="glosa" value="{{ $asiento_automatico->glosa}}" id="glosa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase();">
        </div>
    </div>
</form>
