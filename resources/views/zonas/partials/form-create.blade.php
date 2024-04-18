<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row font-roboto-12">
        <div class="col-md-4 px-0 pr-1">
            <label for="sucursal" class="d-inline">Sucursal</label>
            <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}" id="sucursal_id">
            <input type="text" value="{{ $sucursal->nombre }}" id="sucursal" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <label for="nombre" class="d-inline">Zona</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
        <div class="col-md-2 px-0 pl-1">
            <label for="codigo" class="d-inline">Codigo</label>
            <input type="text" name="codigo" value="{{ old('codigo') }}" id="codigo" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-10 px-0 pr-1">
            <label for="detalle" class="d-inline">Descripcion</label>
            <input type="text" name="detalle" value="{{ old('detalle') }}" id="detalle" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase();">
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-2 px-0 pr-1">
            <label for="filas" class="d-inline">Alto</label> (Max. 20)
            <input type="text" name="filas" value="{{ old('filas') }}" id="filas" class="form-control font-roboto-12 obligatorio" onkeypress="return valideNumberSinDecimal(event);" oninput="obligatorio();">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <label for="columnas" class="d-inline">Ancho</label> (Max. 20)
            <input type="text" name="columnas" value="{{ old('columnas') }}" id="columnas" class="form-control font-roboto-12 obligatorio" onkeypress="return valideNumberSinDecimal(event);" oninput="obligatorio();">
        </div>
        <div class="col-md-8 px-0 pl-1 text-right">
            <br>
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                <i class="fas fa-paper-plane"></i>&nbsp;Procesar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
                &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
            </button>
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-12">
            <div id="tablaContainer"></div>
        </div>
    </div>
</form>