<div class="form-group row">
    <div class="col-md-6 pr-1 font-verdana-bg">
        <label for="empresa" class="d-inline">Empresa</label>
        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
        <input type="text" value="{{ $empresa->nombre_comercial }}" class="form-control font-verdana" disabled>
    </div>
    <div class="col-md-6 pr-1 pl-1 font-verdana-bg">
        <label for="nombre" class="d-inline">Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-verdana obligatorio" oninput="this.value = this.value.toUpperCase()">
    </div>
</div>