<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
    <div class="form-group row font-roboto-12">
        <div class="col-md-4 px-1 pr-1">
            <select name="sucursal_id" id="sucursal_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($sucursales as $index => $value)
                    <option value="{{ $index }}" @if(request('sucursal_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 pr-1 pl-1">
            <select id="zona_id" name="zona_id" class="form-control font-verdana-bg select2">
                <option value="">--Seleccionar--</option>
            </select>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="numero" placeholder="--Numero--" value="{{ request('numero') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-2 px-1 pl-1">
            <input type="text" name="sillas" placeholder="--Sillas--" value="{{ request('sillas') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-4 px-1 pr-1">
            <input type="text" name="detalle" placeholder="--Detalle--" value="{{ request('detalle') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <select name="estado" id="estado" class="form-control">
                <option value="">-</option>
                @foreach ($estados as $index => $value)
                    <option value="{{ $index }}" @if(request('estado') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1">
        @can('mesas.create')
            <span class="tts:right tts-slideIn tts-custom" aria-label="Crear" style="cursor: pointer;">
                <span class="btn btn-outline-success font-roboto-12" onclick="create();">
                    <i class="fas fa-plus fa-fw"></i>
                </span>
            </span>
        @endcan
        <span class="btn btn-outline-danger font-roboto-12 float-right" onclick="limpiar();">
            <i class="fas fa-eraser fa-fw"></i>&nbsp;Limpiar
        </span>
        <span class="btn btn-outline-primary font-roboto-12 float-right mr-1" onclick="search();">
            <i class="fas fa-search fa-fw"></i>&nbsp;Buscar
        </span>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
