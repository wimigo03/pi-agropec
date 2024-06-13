<form action="#" method="get" id="form">
    <div class="form-group row">
        <div class="col-md-2 px-1 pr-1">
            <input type="text" name="fecha_i" value="{{ old('fecha_i') }}" id="fecha_i" placeholder="(desde) - dd-mm-aaaa" class="form-control font-roboto-12 obligatorio" data-language="es">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="fecha_f" value="{{ old('fecha_f') }}" id="fecha_f" placeholder="(hasta) - dd-mm-aaaa" class="form-control font-roboto-12 obligatorio" data-language="es">
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1">
        @can('tipo.cambio.create')
            <span class="btn btn-outline-success font-roboto-12" onclick="create();">
                <i class="fas fa-plus fa-fw"></i>
            </span>
        @endcan
        <span class="btn btn-outline-danger font-roboto-12 float-right" onclick="limpiar();">
            <i class="fas fa-eraser fa-fw"></i>&nbsp;Limpiar
        </span>
        <span class="btn btn-outline-primary font-roboto-12 mr-1 float-right" onclick="search();">
            <i class="fas fa-search fa-fw"></i>&nbsp;Buscar
        </span>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
