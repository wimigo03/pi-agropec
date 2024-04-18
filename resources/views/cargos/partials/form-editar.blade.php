<div class="form-group row">
    <div class="col-md-7 pr-1 font-verdana-bg">
        <label for="cliente_id" class="d-inline">Cliente</label>
        <input type="hidden" name="cliente_id" value="{{ $cargo->cliente_id }}">
        <input type="text" value="{{ $cargo->cliente->razon_social }}" class="form-control font-verdana-bg" disabled>
    </div>
    <div class="col-md-5 pl-1 font-verdana-bg">
        <label for="empresa_id" class="d-inline">Empresa</label>
        <input type="hidden" name="empresa_id" value="{{ $cargo->empresa_id }}" id="empresa_id">
        <input type="text" value="{{ $cargo->empresa->nombre_comercial }}" class="form-control font-verdana-bg" disabled>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-7 pr-1 font-verdana-bg">
        <label for="parent_id" class="d-inline">Dependiente de</label>
        <input type="hidden" name="parent_id" value="{{ $cargo->parent != null ? $cargo->parent->id : null }}">
        <input type="text" value="{{ $cargo->parent != null ? $cargo->parent->nombre : null }}" class="form-control font-verdana-bg" disabled>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-5 pr-1 font-verdana-bg">
        <label for="cargo" class="d-inline">Cargo <i class="fas fa-asterisk"></i></label>
        <input type="text" name="cargo" value="{{ $cargo->nombre }}" class="form-control font-verdana-bg" oninput="this.value = this.value.toUpperCase()">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-verdana-bg">
        <label for="email" class="d-inline">Correo Electronico</label>
        <input type="text" name="email" value="{{ $cargo->email }}" class="form-control font-verdana-bg" oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class="col-md-8 pl-1 font-verdana-bg">
        <label for="descripcion" class="d-inline">Descripcion</label>
        <input type="text" name="descripcion" value="{{ $cargo->descripcion }}" class="form-control font-verdana-bg" oninput="this.value = this.value.toUpperCase()">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-verdana-bg">
        <label for="alias" class="d-inline">Alias</label>
        <select name="alias" id="alias" class="form-control font-verdana-bg">
            <option value="">--Seleccionar--</option>
            <option value="SI" @if($cargo->alias == 'SI') selected @endif >SI</option>
            <option value="NO" @if($cargo->alias == 'NO') selected @endif >NO</option>
        </select>
    </div>
    <div class="col-md-3 pl-1 font-verdana-bg">
        <label for="tipo" class="d-inline">Tipo <i class="fas fa-asterisk"></i></label>
        <select name="tipo" id="tipo" class="form-control font-verdana-bg">
            <option value="">--Seleccionar--</option>
            @foreach ($tipos as $index => $value)
                <option value="{{ $index }}" @if($cargo->tipo == $index) selected @endif >{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>