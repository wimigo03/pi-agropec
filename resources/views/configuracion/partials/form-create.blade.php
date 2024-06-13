<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-1 pr-1 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($empresas as $index => $value)
                    <option value="{{ $index }}" @if(request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 pr-1 pl-1">
            <label for="tipo" class="d-inline">Tipo</label>
            <select name="tipo" id="tipo" class="form-control font-roboto-12 select2">
                <option value="">--Seleccionar--</option>
                @foreach ($tipos as $index => $value)
                    <option value="{{ $index }}" @if(old('tipo') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 pr-1 pl-1">
            <label for="nombre" class="d-inline">Nombre (Sin espacios)</label>
            <input name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase();">
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 pr-1 pl-1">
            <label for="detalle" class="d-inline">Descripcion</label>
            <textarea name="detalle" value="{{ old('detalle') }}" id="detalle" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase();"></textarea>
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1 text-right">
        <span class="btn btn-outline-primary font-roboto-12" onclick="procesar();">
            <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Procesar
        </span>
        <span class="btn btn-outline-danger font-roboto-12" onclick="cancelar();">
            <i class="fas fa-times fa-fw"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
