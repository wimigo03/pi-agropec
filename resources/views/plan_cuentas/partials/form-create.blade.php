<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row">
        <div class="col-md-5 pr-1 font-roboto-12">
            <label for="cliente_id" class="d-inline">Cliente</label>
            <input type="hidden" name="cliente_id" value="{{ $empresa->cliente_id }}">
            <input type="text" value="{{ $empresa->cliente->razon_social }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-5 pr-1 pl-1 font-roboto-12">
            <label for="empresa_id" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
            <input type="text" value="{{ $empresa->nombre_comercial }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 pl-1 font-roboto-12">
            <label for="moneda_id" class="d-inline">Moneda</label>
            <input type="hidden" name="moneda_id" value="2" id="moneda_id">
            <input type="text" value="BOLIVIANOS" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6 pr-1 font-roboto-12">
            <label for="plan_cuenta" class="d-inline">Plan de Cuenta</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control font-roboto-12 obligatorio intro" id="nombre" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
    </div>
</form>