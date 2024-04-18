<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row">
        <div class="col-md-3 px-0 pr-1">
            <select name="tipo_precio_id" id="tipo_precio_id" class="form-control">
                <option value="">-</option>
                @foreach ($tipo_precios as $index => $value)
                    <option value="{{ $index }}" @if(request('tipo_precio_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <select name="categoria_master_id" id="categoria_master_id" class="form-control">
                <option value="">-</option>
                @foreach ($categorias_master as $index => $value)
                    <option value="{{ $index }}" @if(request('categoria_master_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <select id="categoria_id" name="categoria_id" class="form-control font-roboto-12">
                <option value="">--Seleccionar--</option>
            </select>
        </div>
        <div class="col-md-3 px-0 pl-1">
            <input type="text" name="codigo" placeholder="--Codigo--" value="{{ request('codigo') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6 px-0 pr-1">
            <input type="text" name="producto" placeholder="--Producto--" value="{{ request('producto') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        {{--<div class="col-md-2 pr-1 pl-1">
            <select name="estado" id="estado" class="form-control">
                @foreach ($estados as $index => $value)
                    <option value="{{ $index }}" @if(request('estado') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>--}}
        <div class="col-md-6 px-0 pl-1 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="search();">
                <i class="fas fa-search fa-fw"></i>&nbsp;Buscar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
                <i class="fas fa-eraser fa-fw"></i>&nbsp;Limpiar
            </button>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
</form>
