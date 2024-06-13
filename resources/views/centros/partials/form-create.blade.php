<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row align-items-center">
        <div class="col-md-5 px-1 pr-1 font-roboto-12 text-right">
            <label for="empresa" class="d-inline">Empresa:</label>
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <select name="empresa_id" id="empresa_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($empresas as $index => $value)
                    <option value="{{ $index }}" @if(request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row align-items-center">
        <div class="col-md-5 px-1 pr-1 font-roboto-12 text-right">
            <label for="nombre" class="d-inline">Nombre del centro contable:</label>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase();">
        </div>
    </div>
    <div class="form-group row align-items-center">
        <div class="col-md-5 px-1 pr-1 font-roboto-12 text-right">
            <label for="abreviatura" class="d-inline">Codigo:</label>
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <input type="text" name="abreviatura" value="{{ old('abreviatura') }}" id="abreviatura" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase();">
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
