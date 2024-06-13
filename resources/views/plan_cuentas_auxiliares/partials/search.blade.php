<form action="#" method="get" id="form">
    <div class="form-group row font-roboto-12">
        <div class="col-md-3 px-1 pr-1">
            <select name="empresa_id" id="empresa_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($empresas as $index => $value)
                    <option value="{{ $index }}" @if(request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 pr-1 pl-1">
            <input type="text" name="nombre" placeholder="--Auxiliar--" value="{{ request('nombre') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <select name="tipo" id="tipo" class="form-control">
                <option value="">-</option>
                @foreach ($tipos as $index => $value)
                    <option value="{{ $index }}" @if(request('tipo') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
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
        @can('plan.cuentas.auxiliar.create')
            <span class="btn btn-outline-success font-roboto-12" onclick="create();">
                <i class="fas fa-plus"></i>&nbsp;
            </span>
        @endcan
        <span class="btn btn-outline-danger font-roboto-12 float-right" onclick="limpiar();">
            <i class="fas fa-eraser"></i>&nbsp;Limpiar
        </span>
        <span class="btn btn-outline-primary font-roboto-12 float-right mr-1" onclick="search();">
            <i class="fas fa-search"></i>&nbsp;Buscar
        </span>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
