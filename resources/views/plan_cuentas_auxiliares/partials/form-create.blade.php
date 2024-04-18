<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row abs-center">
        <div class="col-md-4 font-roboto-12">
            <label for="nombre" class="d-inline">Nombre Auxiliar</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase();">
        </div>
    </div>
</form>
