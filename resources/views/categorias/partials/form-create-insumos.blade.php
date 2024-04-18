<div class="form-group row">
    <div class="col-md-7 px-0 pr-1 font-roboto-12">
        <label for="cliente_id" class="d-inline">Cliente</label>
        <input type="hidden" name="cliente_id" value="{{ $categoria->cliente_id }}">
        <input type="text" value="{{ $categoria->cliente->razon_social }}" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-5 px-0 pl-1 font-roboto-12">
        <label for="empresa_id" class="d-inline">Empresa</label>
        <input type="hidden" name="empresa_id" value="{{ $categoria->empresa_id }}" id="empresa_id">
        <input type="text" value="{{ $categoria->empresa->nombre_comercial }}" class="form-control font-roboto-12" disabled>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-5 px-0 pr-1 font-roboto-12">
        <label for="parent_id" class="d-inline">Dependiente de</label>
        <input type="hidden" name="parent_id" value="{{ $categoria->id }}">
        <input type="text" value="{{ $categoria->nombre }}" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-5 pr-1 pl-1 font-roboto-12">
        <label for="categoria" class="d-inline">Categoria</label>
        <input type="text" name="categoria" value="{{ old('categoria') }}" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class="col-md-2  px-0 pl-1 font-roboto-12">
        <label for="codigo" class="d-inline">Codigo</label>
        <input type="text" name="codigo" value="{{ old('codigo') }}" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase()">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6 px-0 pr-1 font-roboto-12">
        <label for="detalle" class="d-inline">Detalle</label>
        <input type="text" name="detalle" value="{{ old('detalle') }}" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class="col-md-3 pr-1 pl-1 font-roboto-12">
        <label for="tipo" class="d-inline">tipo</label>
        <input type="hidden" name="tipo" value="2" class="form-control font-roboto-12">
        <input type="text" value="INSUMOS" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-3 px-0 pl-1 font-roboto-12">
        <label for="plan_cuenta" class="d-inline">Plan Cuenta</label>
        <select name="plan_cuenta_id" id="plan_cuenta_id" class="form-control font-roboto-12 select2">
            <option value="">--Seleccionar--</option>
            @foreach ($plan_cuentas as $index => $value)
                <option value="{{ $index }}" @if(old('plan_cuenta_id') == $index) selected @endif >{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>