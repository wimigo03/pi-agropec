<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
            <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()" disabled>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <label for="sucursal" class="d-inline">Sucursal</label>
            <select name="sucursal_id" id="sucursal_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($sucursales as $index => $value)
                    <option value="{{ $index }}" @if(old('sucursal_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="user" class="d-inline">Asignar a</label>
            <select name="user_id" id="user_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($users as $index => $value)
                    <option value="{{ $index }}" @if(old('user_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="monto" class="d-inline">Monto(Bs.)</label>
            <input type="text" name="monto" value="{{ old('monto') }}" id="monto" class="form-control font-roboto-12">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-9 px-1 pr-1 font-roboto-12">
            <label for="concepto" class="d-inline">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase();">{{ old('observaciones') }}</textarea>
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1 text-right">
        <span class="btn btn-outline-primary font-roboto-12" onclick="procesar();">
            <i class="fas fa-paper-plane"></i>&nbsp;Procesar
        </span>
        <span class="btn btn-outline-danger font-roboto-12" onclick="cancelar();">
            &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
