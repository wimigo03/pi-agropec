<form action="#" method="get" id="form">
    <div class="form-group row font-roboto-12">
        <div class="col-md-1 px-1 pr-1">
            <input type="text" name="codigo" placeholder="--Codigo--" value="{{ request('codigo') }}" class="form-control font-roboto-12 intro" onkeypress="return valideNumberInteger(event);">
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <select name="pais_id" id="pais_id" class="form-control">
                <option value="">-</option>
                @foreach ($paises as $index => $value)
                    <option value="{{ $index }}" @if(request('pais_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="fecha" value="{{ request('fecha') }}" id="fecha" placeholder="--Inicio--" class="form-control font-roboto-12 intro" data-language="es">
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <input type="text" name="razon_social" placeholder="--Razon Social--" value="{{ request('razon_social') }}" class="form-control font-roboto-12 intro">
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <input type="text" name="nombre" placeholder="--Nombre--" value="{{ request('nombre') }}" class="form-control font-roboto-12 intro">
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="nit" placeholder="--Nit--" value="{{ request('nit') }}" class="form-control font-roboto-12 intro">
        </div>
        <div class="col-md-2 px-1 pl-1">
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
    <div class="col-md-12">
        <span class="btn btn-outline-success ml-1 font-roboto-12" onclick="create();">
            <i class="fas fa-plus fa-fw"></i>
        </span>
        <span class="btn btn-outline-danger font-roboto-12 mr-1 float-right" onclick="limpiar();">
            <i class="fas fa-eraser fa-fw" aria-hidden="true"></i>&nbsp;Limpiar
        </span>
        <span class="btn btn-outline-primary font-roboto-12 mr-1 float-right" onclick="procesar();">
            <i class="fa fa-search fa-fw" aria-hidden="true"></i>&nbsp;Buscar
        </span>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
