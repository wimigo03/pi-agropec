<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-0 pr-1">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="text" value="{{ $empresa->nombre_comercial }}" class="form-control font-roboto-12" disabled>
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
