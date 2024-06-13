<form action="#" method="get" id="form">
    <div class="form-group row font-roboto-12">
        <div class="col-md-4 px-1 pr-1">
            <select name="empresa_id" id="empresa_id" class="form-control">
                <option value="">-</option>
                @foreach ($empresas as $index => $value)
                    <option value="{{ $index }}" @if(request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
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
            <input type="text" name="fecha_i" value="{{ request('fecha_i') }}" id="fecha_i" placeholder="(desde) - dd-mm-aaaa" class="form-control font-roboto-12 intro" data-language="es">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="fecha_f" value="{{ request('fecha_f') }}" id="fecha_f" placeholder="(hasta) - dd-mm-aaaa" class="form-control font-roboto-12 intro" data-language="es">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="nro_comprobante" value="{{ request('nro_comprobante') }}" id="nro_comprobante" placeholder="--Nro. Comprobante--" class="form-control font-roboto-12 intro">
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-4 px-1 pl-1">
            <input type="text" name="concepto" value="{{ request('concepto') }}" id="concepto" placeholder="--Concepto--" class="form-control font-roboto-12 intro">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="monto" value="{{ request('monto') }}" id="monto" placeholder="--Monto--" class="form-control font-roboto-12 intro" onkeypress="return valideNumberConDecimal(event);">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <select name="estado" id="estado" class="form-control">
                <option value="">-</option>
                @foreach ($estados as $index => $value)
                    <option value="{{ $index }}" @if(request('estado') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <select name="user_id" id="user_id" class="form-control">
                <option value="">-</option>
            </select>
        </div>
        @can('comprobantef.index')
            <div class="col-md-1 pr-1 pl-1">
                <select name="copia" id="copia" class="form-control">
                    <option value="">-</option>
                    <option value="1" @if(request('copia') == '1') selected @endif >CON COPIA</option>
                    <option value="2" @if(request('copia') == '2') selected @endif >SIN COPIA</option>
                </select>
            </div>
        @endcan
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1">
        @can('comprobantef.index')
            <span class="tts:right tts-slideIn tts-custom" aria-label="Comprobantes" style="cursor: pointer;">
                <span class="btn btn-outline-secondary font-roboto-12" onclick="comprobantesf();">
                    <i class="fa-solid fa-file-invoice-dollar fa-fw"></i>
                </span>
            </span>
        @endcan
        @can('comprobante.create')
            <span class="tts:right tts-slideIn tts-custom" aria-label="Crear Comprobante" style="cursor: pointer;">
                <span class="btn btn-outline-success font-roboto-12" onclick="create();">
                    <i class="fas fa-plus fa-fw"></i>
                </span>
            </span>
        @endcan
        @can('comprobante.excel')
            <span class="tts:right tts-slideIn tts-custom" aria-label="Exportar" style="cursor: pointer;">
                <span class="btn btn-outline-success font-roboto-12" onclick="excel();">
                    <i class="fas fa-file-excel fa-fw"></i> Excel
                </span>
            </span>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        @endcan
        <span class="btn btn-outline-danger font-roboto-12 float-right" onclick="limpiar();">
            <i class="fas fa-eraser"></i>&nbsp;Limpiar
        </span>
        <span class="btn btn-outline-primary font-roboto-12 mr-1 float-right" onclick="search();">
            <i class="fas fa-search"></i>&nbsp;Buscar
        </span>
        <i class="fa fa-spinner fa-spin fa-lg spinner-btn-send" style="display: none;"></i>
    </div>
</div>
