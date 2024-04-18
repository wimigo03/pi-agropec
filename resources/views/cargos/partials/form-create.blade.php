<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row">
        <div class="col-md-7 px-0 pr-1 font-roboto-12">
            <label for="cliente_id" class="d-inline">Cliente</label>
            <input type="hidden" name="cliente_id" value="{{ $cargo->cliente_id }}">
            <input type="text" value="{{ $cargo->cliente->razon_social }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-5 px-0 pl-1 font-roboto-12">
            <label for="empresa_id" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $cargo->empresa_id }}" id="empresa_id">
            <input type="text" value="{{ $cargo->empresa->nombre_comercial }}" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-7 px-0 pr-1 font-roboto-12">
            <label for="parent_id" class="d-inline">Dependiente de</label>
            <input type="hidden" name="parent_id" value="{{ $cargo->id }}">
            <input type="text" value="{{ $cargo->nombre }}" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-5 px-0 pr-1 font-roboto-12">
            <label for="cargo" class="d-inline">Cargo <i class="fas fa-asterisk"></i></label>
            <input type="text" name="cargo" value="{{ old('cargo') }}" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4 px-0 pr-1 font-roboto-12">
            <label for="email" class="d-inline">Correo Electronico</label>
            <input type="text" name="email" value="{{ old('email') }}" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-8 px-0 pl-1 font-roboto-12">
            <label for="descripcion" class="d-inline">Descripcion</label>
            <input type="text" name="descripcion" value="{{ old('descripcion') }}" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4 px-0 pr-1 font-roboto-12">
            <label for="alias" class="d-inline">Alias</label>
            <select name="alias" id="alias" class="form-control font-roboto-12">
                <option value="">--Seleccionar--</option>
                <option value="SI" @if(old('alias') == 'SI') selected @endif >SI</option>
                <option value="NO" @if(old('alias') == 'NO') selected @endif >NO</option>
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="tipo" class="d-inline">Tipo <i class="fas fa-asterisk"></i></label>
            <select name="tipo" id="tipo" class="form-control font-roboto-12">
                <option value="">--Seleccionar--</option>
                @foreach ($tipos as $index => $value)
                    <option value="{{ $index }}" @if(old('tipo') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12" id="cuenta_contable">
            <label for="plan_cuenta_id" class="d-inline">Plan de Cuenta</label>
            <select name="plan_cuenta_id" id="plan_cuenta_id" class="form-control font-roboto-12">
                <option value="">--Seleccionar--</option>
                @foreach ($cuentas_contables as $index => $value)
                    <option value="{{ $index }}" @if(old('plan_cuenta_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>
