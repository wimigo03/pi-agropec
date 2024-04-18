<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-3 px-0 pr-1">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="text" value="{{ $empresa->nombre_comercial }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <label for="nombre" class="d-inline">Tipo</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12 obligatorio intro" oninput="this.value = this.value.toUpperCase(); obligatorio();">
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-6 pr-1 pl-1">
            <label for="observaciones" class="d-inline">Observaciones</label>
            <textarea name="observaciones" value="{{ old('observaciones') }}" id="observaciones" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase();"></textarea>
        </div>
    </div>
</form>