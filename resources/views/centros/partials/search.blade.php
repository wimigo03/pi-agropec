<form action="#" method="get" id="form">
    <div class="form-group row font-roboto-12">
        <div class="col-md-3 px-1 pr-1">
            <select name="empresa_id" id="empresa_id" class="form-control">
                <option value="">-</option>
                @foreach ($empresas as $index => $value)
                    <option value="{{ $index }}" @if(request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <select name="centro" id="centro" class="form-control">
                <option value="">-</option>
                @foreach ($centros as $index => $value)
                    <option value="{{ $index }}" @if(request('centro') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <select name="sub_centro" id="sub_centro" class="form-control">
                <option value="">-</option>
                @foreach ($_subcentros as $index => $value)
                    <option value="{{ $index }}" @if(request('sub_centro') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 pr-1 pl-1">
            <input type="text" name="codigo" value="{{ request('codigo') }}" id="codigo" placeholder="--Codigo--" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase();">
        </div>
        <div class="col-md-2 px-1 pl-1">
            <select name="tipo" id="tipo" class="form-control">
                <option value="">-</option>
                @foreach ($tipos as $index => $value)
                    <option value="{{ $index }}" @if(request('tipo') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-2 px-1 pr-1">
            <input type="text" name="fecha" value="{{ request('fecha') }}" id="fecha" placeholder="--Creacion--" class="form-control font-roboto-12 intro" data-language="es">
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
<div class="form-group row abs-center">
    <div class="col-md-12 px-1">
        @can('centros.create')
            <span class="btn btn-outline-success font-roboto-12" onclick="create();">
                <i class="fas fa-plus-square fa-fw"></i> Centro
            </span>
        @endcan
        @can('sub.centros.create')
            <span class="btn btn-outline-primary font-roboto-12" onclick="subcreate();">
                <i class="fas fa-plus-square fa-fw"></i> Sub Centro
            </span>
        @endcan
        <span class="btn btn-outline-danger font-roboto-12 float-right" onclick="limpiar();">
            <i class="fas fa-eraser"></i>&nbsp;Limpiar
        </span>
        <span class="btn btn-outline-primary font-roboto-12 mr-1 float-right" onclick="search();">
            <i class="fas fa-search"></i>&nbsp;Buscar
        </span>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
