<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="tipo_cambio_id" value="{{ $tipo_cambio->id }}">
    <div class="form-group row">
        <div class="col-md-4 px-0 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="fecha" class="d-inline">Fecha</label>
            <input type="text" value="{{ \Carbon\Carbon::parse($tipo_cambio->fecha)->format('d/m/Y') }}" id="fecha" placeholder="dd/mm/aaaa" class="form-control font-roboto-12 obligatorio" data-language="es" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-0 font-roboto-12">
            <label for="ufv" class="d-inline">UFV</label>
            <input type="text" name="ufv" value="{{ $tipo_cambio->ufv }}" id="ufv" class="form-control font-roboto-12 obligatorio intro" onkeypress="return valideNumberConDecimal(event);" oninput="obligatorio();">
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="oficial" class="d-inline">Oficial</label>
            <input type="text" name="oficial" value="{{ $tipo_cambio->dolar_oficial }}" id="oficial" class="form-control font-roboto-12 obligatorio intro" onkeypress="return valideNumberConDecimal(event);" oninput="obligatorio();">
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="compra" class="d-inline">Compra</label>
            <input type="text" name="compra" value="{{ $tipo_cambio->dolar_compra }}" id="compra" class="form-control font-roboto-12 obligatorio intro" onkeypress="return valideNumberConDecimal(event);" oninput="obligatorio();">
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="venta" class="d-inline">venta</label>
            <input type="text" name="venta" value="{{ $tipo_cambio->dolar_venta }}" id="venta" class="form-control font-roboto-12 obligatorio intro" onkeypress="return valideNumberConDecimal(event);" oninput="obligatorio();">
        </div>
    </div>
</form>
