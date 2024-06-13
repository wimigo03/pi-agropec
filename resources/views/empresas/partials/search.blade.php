<form action="#" method="get" id="form">
    <input type="hidden" name="pi_cliente_id" value="{{ $cliente->id }}" id="pi_cliente_id">
    <div class="form-group row font-roboto-12">
        <div class="col-md-2 px-1 pr-1">
            <input type="text" name="codigo" placeholder="--Codigo--" value="{{ request('codigo') }}" class="form-control font-roboto-12 intro" onkeypress="return valideNumberInteger(event);">
        </div>
        <div class="col-md-5 pr-1 pl-1">
            <input type="text" name="nombre_comercial" placeholder="--Nombre Comercial--" value="{{ request('nombre_comercial') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <input type="text" name="telefono" placeholder="--Telefono--" value="{{ request('telefono') }}" class="form-control font-roboto-12 intro" onkeypress="return valideNumberInteger(event);">
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
        <span class="tts:right tts-slideIn tts-custom" aria-label="Ir atras">
            <span class="btn btn-outline-primary ml-1 font-roboto-12" onclick="retroceder();">
                <i class="fas fa-angle-double-left fa-fw"></i>
            </span>
        </span>
        <span class="tts:right tts-slideIn tts-custom" aria-label="Registrar empresa">
            <span class="btn btn-outline-success font-roboto-12" onclick="create();">
                <i class="fas fa-plus fa-fw"></i>
            </span>
        </span>
        <span class="btn btn-outline-danger font-roboto-12 mr-1 float-right" onclick="limpiar();">
            <i class="fas fa-eraser fa-fw" aria-hidden="true"></i>&nbsp;Limpiar
        </span>
        <span class="btn btn-outline-primary font-roboto-12 mr-1 float-right" onclick="procesar();">
            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar
        </span>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
