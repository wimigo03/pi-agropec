<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row font-roboto-12">
        <div class="col-md-2 px-0 pr-1">
            <input type="text" name="fecha_i" value="{{ request('fecha_i') }}" id="fecha_i" placeholder="(desde) - dd/mm/aaaa" class="form-control font-roboto-12 intro" data-language="es" onkeyup=countCharsI(this);>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="fecha_f" value="{{ request('fecha_f') }}" id="fecha_f" placeholder="(hasta) - dd/mm/aaaa" class="form-control font-roboto-12 intro" data-language="es" onkeyup=countCharsF(this);>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="nro_comprobante" value="{{ request('nro_comprobante') }}" id="nro_comprobante" placeholder="--Nro. Comprobante--" class="form-control font-roboto-12 intro">
        </div>
        <div class="col-md-4 pr-1 pl-1">
            <input type="text" name="concepto" value="{{ request('concepto') }}" id="concepto" placeholder="--Concepto--" class="form-control font-roboto-12 intro">
        </div>
        <div class="col-md-2 px-0 pl-1">
            <select name="tipo" id="tipo" class="form-control">
                <option value="">-</option>
                @foreach ($tipos as $index => $value)
                    <option value="{{ $index }}" @if(request('tipo') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-2 px-0 pr-1">
            <select name="estado" id="estado" class="form-control">
                <option value="">-</option>
                @foreach ($estados as $index => $value)
                    <option value="{{ $index }}" @if(request('estado') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="monto" value="{{ request('monto') }}" id="monto" placeholder="--Monto--" class="form-control font-roboto-12 intro" onkeypress="return valideNumberConDecimal(event);">
        </div>
        @can('comprobantef.index')
            <div class="col-md-2 pr-1 pl-1">
                <select name="copia" id="copia" class="form-control">
                    <option value="">-</option>
                    <option value="1" @if(request('copia') == '1') selected @endif >CON COPIA</option>
                    <option value="2" @if(request('copia') == '2') selected @endif >SIN COPIA</option>
                </select>
            </div>
        @endcan
    </div>
</form>
