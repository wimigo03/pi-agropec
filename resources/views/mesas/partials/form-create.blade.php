<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="pi_cliente_id" value="{{ $empresa->pi_cliente_id }}" id="pi_cliente_id">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row font-roboto-12">
        <div class="col-md-4 px-1 pr-1">
            <label for="sucursal" class="d-inline">Sucursal</label>
            <div class="select2-container--obligatorio" id="obligatorio_sucursal_id">
                <select name="sucursal_id" id="sucursal_id" class="form-control select2" onchange="obligatorio();">
                    <option value="">-</option>
                    @foreach ($sucursales as $index => $value)
                        <option value="{{ $index }}" @if(old('sucursal_id') == $index) selected @endif >{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4 pr-1 pl-1">
            <label for="zona" class="d-inline">Zona</label>
            <div class="select2-container--obligatorio" id="obligatorio_zona_id">
                <select id="zona_id" name="zona_id" class="form-control font-verdana-bg select2" onchange="obligatorio();">
                    <option value="">--Seleccionar--</option>
                </select>
            </div>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <label for="numero" class="d-inline">N° de Mesa</label>
            <input type="text" name="numero" value="{{ old('numero') }}" id="numero" class="form-control font-roboto-12 obligatorio intro" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
        <div class="col-md-2 px-1 pl-1">
            <label for="sillas" class="d-inline">Cant. Sillas</label>
            <input type="text" name="sillas" value="{{ old('sillas') }}" id="sillas" class="form-control font-roboto-12 obligatorio intro" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-11 px-1 pr-1">
            <label for="detalle" class="d-inline">Descripcion</label>
            <input type="text" name="detalle" value="{{ old('detalle') }}" id="detalle" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase();">
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1 text-right">
        <span class="btn btn-outline-primary font-roboto-12" onclick="procesar();">
            <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Procesar
        </span>
        <span class="btn btn-outline-danger font-roboto-12" onclick="cancelar();">
            <i class="fas fa-times fa-fw"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
