<br>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-roboto-12">
        <label for="empresa" class="d-inline">Empresa</label>
        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
        <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()" disabled>
    </div>
    <div class="col-md-4 pr-1 font-roboto-12">
        <label for="nombre" class="d-inline">Nombre</label>
        <input type="text" name="nombre" value="{{ $sucursal->nombre }}" id="nombre" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
    <div class="col-md-4 pl-1 font-roboto-12">
        <label for="ciudad" class="d-inline">Ciudad</label>
        <input type="text" name="ciudad" value="{{ $sucursal->ciudad }}" id="ciudad" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-10 pr-1 font-roboto-12">
        <label for="direccion" class="d-inline">Direccion</label>
        <input type="text" name="direccion" value="{{ $sucursal->direccion }}" id="direccion" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
    <div class="col-md-2 pl-1 font-roboto-12">
        <label for="celular" class="d-inline">Celular</label>
        <input type="text" name="celular" value="{{ $sucursal->celular }}" class="form-control font-roboto-12" onkeypress="return valideNumberSinDecimal(event);">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-3 pr-1 font-roboto-12">
        <label for="estado" class="d-inline">Estado</label>
        <select name="estado" id="estado" class="form-control font-roboto-12 select2">
            <option value="1" @if(old('estado') == '1') selected @endif >HABILITADO</option>
            <option value="2" @if(old('estado') == '2') selected @endif >NO HABILITADO</option>
        </select>
    </div>
</div>
