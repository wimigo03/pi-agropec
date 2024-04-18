<div class="form-group row">
    <div class="col-md-7 pr-1 font-verdana-bg">
        <label for="cliente_id" class="d-inline">Cliente</label>
        <input type="hidden" name="cliente_id" value="{{ $categoria->cliente_id }}">
        <input type="text" value="{{ $categoria->cliente->razon_social }}" class="form-control font-verdana-bg" disabled>
    </div>
    <div class="col-md-5 pl-1 font-verdana-bg">
        <label for="empresa_id" class="d-inline">Empresa</label>
        <input type="hidden" name="empresa_id" value="{{ $categoria->empresa_id }}" id="empresa_id">
        <input type="text" value="{{ $categoria->empresa->nombre_comercial }}" class="form-control font-verdana-bg" disabled>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-5 pr-1 font-verdana-bg">
        <label for="parent_id" class="d-inline">Dependiente de</label>
        <input type="hidden" name="parent_id" value="{{ $categoria->parent != null ? $categoria->parent->id : null }}">
        <input type="text" value="{{ $categoria->parent != null ? $categoria->parent->nombre : null }}" class="form-control font-verdana-bg" disabled>
    </div>
    <div class="col-md-5 pr-1 pl-1 font-verdana-bg">
        <label for="categoria" class="d-inline">Categoria</label>
        <input type="text" name="categoria" value="{{ $categoria->nombre }}" class="form-control font-verdana-bg obligatorio" oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class="col-md-2 pl-1 font-verdana-bg">
        <label for="codigo" class="d-inline">Codigo</label>
        <input type="text" name="codigo" value="{{ $categoria->codigo }}" class="form-control font-verdana-bg obligatorio" oninput="this.value = this.value.toUpperCase()">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6 pr-1 font-verdana-bg">
        <label for="detalle" class="d-inline">Detalle</label>
        <input type="text" name="detalle" value="{{ $categoria->detalle }}" class="form-control font-verdana-bg" oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class="col-md-3 pr-1 pl-1 font-verdana-bg">
        <label for="tipo" class="d-inline">Tipo</label>
        <input type="text" value="{{ $tipo }}" class="form-control font-verdana-bg" disabled>
    </div>
    <div class="col-md-3 pl-1 font-verdana-bg">
        <label for="plan_cuenta" class="d-inline">Plan Cuenta</label>
        <select name="plan_cuenta_id" id="plan_cuenta_id" placeholder="--Seleccionar--" class="form-control select2 {{ $errors->has('plan_cuenta_id') ? 'is-invalid' : '' }}">
            @foreach ($plan_cuentas as $plan_cuenta)
                <option value="{{ $plan_cuenta->id }}"
                    @if($plan_cuenta->id == request('plan_cuenta_id') || (isset($categoria) && $categoria->plan_cuenta_id == $plan_cuenta->id))
                        selected
                    @endif>
                    {{ $plan_cuenta->nombre }}
                </option>
            @endforeach
        </select>
    </div>
</div>