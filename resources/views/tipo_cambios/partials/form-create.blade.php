<form action="#" method="post" id="form" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="fecha" class="d-inline">Fecha</label>
            <input type="text" name="fecha" value="{{ isset($cambio_anterior) ? date('d-m-Y') : old('fecha')  }}" id="fecha" placeholder="dd-mm-aaaa" class="form-control font-roboto-12 obligatorio" data-language="es" oninput="obligatorio();">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="ufv" class="d-inline">UFV</label>
            <input type="text" name="ufv" value="{{ isset($cambio_anterior) ? $cambio_anterior->ufv : old('ufv') }}" id="ufv" class="form-control font-roboto-12 obligatorio intro" onkeypress="return valideNumberConDecimal(event);" oninput="obligatorio();">
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="oficial" class="d-inline">Oficial</label>
            <input type="text" name="oficial" value="{{ isset($cambio_anterior) ? $cambio_anterior->dolar_oficial : old('oficial') }}" id="oficial" class="form-control font-roboto-12 obligatorio intro" onkeypress="return valideNumberConDecimal(event);" oninput="obligatorio();">
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="compra" class="d-inline">Compra</label>
            <input type="text" name="compra" value="{{ isset($cambio_anterior) ? $cambio_anterior->dolar_compra : old('compra') }}" id="compra" class="form-control font-roboto-12 obligatorio intro" onkeypress="return valideNumberConDecimal(event);" oninput="obligatorio();">
        </div>
        <div class="col-md-3 px-1 pl-1 font-roboto-12">
            <label for="venta" class="d-inline">Venta</label>
            <input type="text" name="venta" value="{{ isset($cambio_anterior) ? $cambio_anterior->dolar_venta : old('venta') }}" id="venta" class="form-control font-roboto-12 obligatorio intro" onkeypress="return valideNumberConDecimal(event);" oninput="obligatorio();">
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
