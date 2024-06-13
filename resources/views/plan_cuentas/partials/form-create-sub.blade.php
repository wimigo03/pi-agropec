<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row">
        <div class="col-md-5 px-1 pr-1 font-roboto-12">
            <label for="pi_cliente_id" class="d-inline">Cliente</label>
            <input type="hidden" name="pi_cliente_id" value="{{ $empresa->pi_cliente_id }}">
            <input type="text" value="{{ $empresa->cliente->razon_social }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-5 pr-1 pl-1 font-roboto-12">
            <label for="empresa_id" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
            <input type="text" value="{{ $empresa->nombre_comercial }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 px-1 pl-1 font-roboto-12">
            <label for="moneda_id" class="d-inline">Moneda</label>
            <input type="hidden" name="moneda_id" value="2" id="moneda_id">
            <input type="text" value="BOLIVIANOS" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-2 px-1 pr-1 font-roboto-12">
            <label for="codigo" class="d-inline">Codigo</label>
            <input type="hidden" name="nivel" value="{{ $plan_cuenta->nivel }}" id="nivel">
            <input type="text" name="codigo" value="{{ $plan_cuenta->codigo }}" class="form-control font-roboto-12" readonly>
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="dependiente" class="d-inline">Dependiente de</label>
            <input type="hidden" name="parent_id" value="{{ $plan_cuenta->id }}" id="plancuenta_id">
            <input type="text" value="{{ $plan_cuenta->nombre }}" class="form-control font-roboto-12" readonly>
        </div>
        <div class="col-md-4 px-1 pl-1 font-roboto-12">
            <label for="plan_cuenta" class="d-inline">Plan de Cuenta</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control font-roboto-12 obligatorio intro" id="nombre" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="detalle" class="d-inline">¿Es Cuenta Detalle?</label>
            <input type="checkbox" id="detalle" class="ml-2" name="detalle" {{ old('detalle') ? 'checked' : '' }}>
        </div>
    </div>
    <div class="form-group row" id="cuenta_banco">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="banco" class="d-inline">¿Es una Cuenta de Banco?</label>
            <input type="checkbox" id="banco" class="ml-2" name="banco" {{ old('banco') ? 'checked' : '' }}>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="auxiliar" class="d-inline">¿Es Auxiliar?</label>
            <input type="checkbox" id="auxiliar" class="ml-2" name="auxiliar" {{ old('auxiliar') ? 'checked' : '' }}>
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1 text-right">
        <span class="btns btn btn-outline-primary font-roboto-12" onclick="procesar();">
            <i class="fas fa-paper-plane"></i>&nbsp;Procesar
        </span>
        <span class="btns btn btn-outline-danger font-roboto-12" onclick="cancelar();">
            <i class="fas fa-times"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
