<div class="form-group row">
    <div class="col-md-7 pr-1 font-verdana">
        <label for="cliente_id" class="d-inline">Cliente</label>
        <input type="hidden" name="cliente_id" value="{{ $cargo->cliente_id }}">
        <input type="text" value="{{ $cargo->cliente->razon_social }}" class="form-control form-control-sm input-sm font-verdana" disabled>
    </div>
    <div class="col-md-5 pl-1 font-verdana">
        <label for="empresa_id" class="d-inline">Empresa</label>
        <input type="hidden" name="empresa_id" value="{{ $cargo->empresa_id }}">
        <input type="text" value="{{ $cargo->empresa->nombre_comercial }}" class="form-control form-control-sm input-sm font-verdana" disabled>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-7 pr-1 font-verdana">
        <label for="parent_id" class="d-inline">Dependiente de</label>
        <input type="hidden" name="parent_id" value="{{ $cargo->parent->id }}">
        <input type="text" value="{{ $cargo->parent->nombre }}" class="form-control form-control-sm input-sm font-verdana" disabled>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-5 pr-1 font-verdana">
        <label for="cargo" class="d-inline">Cargo <i class="fas fa-asterisk"></i></label>
        <input type="text" name="cargo" value="{{ $cargo->nombre }}" class="form-control form-control-sm input-sm font-verdana" oninput="this.value = this.value.toUpperCase()">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-verdana">
        <label for="email" class="d-inline">Correo Electronico</label>
        <input type="text" name="email" value="{{ $cargo->email }}" class="form-control form-control-sm input-sm font-verdana" oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class="col-md-8 pl-1 font-verdana">
        <label for="descripcion" class="d-inline">Descripcion</label>
        <input type="text" name="descripcion" value="{{ $cargo->descripcion }}" class="form-control form-control-sm input-sm font-verdana" oninput="this.value = this.value.toUpperCase()">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-verdana">
        <label for="alias" class="d-inline">Alias</label>
        <input type="text" name="alias" value="{{ $cargo->alias }}" class="form-control form-control-sm input-sm font-verdana" oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class="col-md-3 pl-1 font-verdana">
        <label for="tipo" class="d-inline">Tipo <i class="fas fa-asterisk"></i></label>
        <select name="tipo" id="tipo" class="form-control form-control-sm input-sm font-verdana">
            <option value="">--Seleccionar--</option>
            @foreach ($tipos as $index => $value)
                <option value="{{ $index }}" @if($cargo->tipo == $index) selected @endif >{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>