<br>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-roboto-12">
        <label for="empresa" class="d-inline">Empresa</label>
        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
        <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()" disabled>
    </div>
    <div class="col-md-4 pr-1 pl-1 font-roboto-12">
        <label for="nombre" class="d-inline">Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
    <div class="col-md-4 pl-1 font-roboto-12">
        <label for="ciudad" class="d-inline">Ciudad</label>
        <input type="text" name="ciudad" value="{{ old('ciudad') }}" id="ciudad" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-10 pr-1 font-roboto-12">
        <label for="direccion" class="d-inline">Direccion</label>
        <input type="text" name="direccion" value="{{ old('direccion') }}" id="direccion" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
    <div class="col-md-2 pl-1 font-roboto-12">
        <label for="celular" class="d-inline">Telefono</label>
        <input type="text" name="celular" value="{{ old('celular') }}" class="form-control font-roboto-12" onkeypress="return valideNumberSinDecimal(event);">
    </div>
</div>
