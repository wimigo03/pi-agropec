<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row abs-center">
        <div class="col-md-4 px-0 pr-1">
            <input type="text" name="nombre" placeholder="-" value="{{ request('nombre') }}" class="form-control font-verdana-bg intro text-center">
        </div>
    </div>
</form>
<div class="form-group row abs-center">
    <div class="col-md-2 px-0 pr-1">
        @can('roles.create')
            <button class="btn btn-outline-success font-verdana" type="button" onclick="create();">
                &nbsp;<i class="fas fa-plus"></i>&nbsp;
            </button>
        @endcan
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
    <div class="col-md-4 px-0 pl-1 text-right">
        <button class="btn btn-outline-primary font-verdana" type="button" onclick="search();">
            &nbsp;<i class="fas fa-search"></i>&nbsp;Buscar
        </button>
        <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
            &nbsp;<i class="fas fa-eraser"></i>&nbsp;Limpiar
        </button>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
