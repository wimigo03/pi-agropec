<div class="form-group row">
    <div class="col-md-3 px-0 pr-1 font-roboto-12">
        <label for="empresa" class="d-inline">Empresa</label>
        <input type="hidden" value="{{ $caja_venta->empresa_id }}" id="empresa_id">
        <input type="hidden" value="{{ $caja_venta->id }}" id="caja_venta_id">
        <input type="text" value="{{ $caja_venta->empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-4 pr-1 pl-1 font-roboto-12">
        <label for="sucursal" class="d-inline">Sucursal</label>
        <input type="text" value="{{ $caja_venta->sucursal->nombre }}" id="sucursal" class="form-control font-roboto-12" disabled>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2 px-0 pr-1 font-roboto-12">
        <label for="user" class="d-inline">Asignar a</label>
        <input type="text" value="{{ strtoupper($caja_venta->user->username) }}" id="user" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="user_asignado" class="d-inline">Asignado por</label>
        <input type="text" value="{{ $caja_venta->user_asignado != null ? strtoupper($caja_venta->user_asignado->username) : '' }}" id="user_asignado" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="codigo" class="d-inline">Codigo</label>
        <input type="text" value="{{ $caja_venta->codigo }}" id="codigo" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="fecha" class="d-inline">Fecha Registro</label>
        <input type="text" value="{{ \Carbon\Carbon::parse($caja_venta->fecha_registro)->format('d/m/Y') }}" id="fecha" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="monto" class="d-inline">Monto(Bs.)</label>
        <input type="text" value="{{ number_format($caja_venta->monto_apertura,2,'.',',') }}" id="monto" class="form-control font-roboto-12">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-9 px-0 pr-1 font-roboto-12">
        <label for="concepto" class="d-inline">Observaciones</label>
        <textarea name="observaciones" id="observaciones" class="form-control font-roboto-12 intro" disabled>{{ $caja_venta->observaciones }}</textarea>
    </div>
</div>
