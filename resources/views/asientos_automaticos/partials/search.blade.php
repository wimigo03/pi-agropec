<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row font-roboto-12">
        <div class="col-md-3 px-0 pr-1">
            <select name="modulo_id" id="modulo_id" class="form-control">
                <option value="">-</option>
                @foreach ($modulos as $index => $value)
                    <option value="{{ $index }}" @if(request('modulo_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 pr-1 pl-1">
            <select name="plan_cuenta_id" id="plan_cuenta_id" class="form-control">
                <option value="">-</option>
                @foreach ($plan_cuentas as $index => $value)
                    <option value="{{ $index }}" @if(request('plan_cuenta_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <input type="text" name="concepto" value="{{ request('concepto') }}" id="concepto" placeholder="--Concepto--" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
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
        <div class="col-md-4 px-0 pr-1">
            <input type="text" name="glosa" value="{{ request('glosa') }}" id="glosa" placeholder="--Glosa--" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
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
