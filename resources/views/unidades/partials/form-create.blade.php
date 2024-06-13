<div class="card-body">
    <div class="form-group row">
        <div class="col-md-6 px-1 pr-1 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
            <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-5 px-1 pr-1 font-roboto-12">
            <label for="nombre" class="d-inline">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="codigo" class="d-inline">Codigo</label>
            <input type="text" name="codigo" value="{{ old('codigo') }}" id="codigo" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-4 px-1 pl-1 font-roboto-12">
            <label for="tipo" class="d-inline">Tipo</label>
            <div class="select2-container--obligatorio" id="obligatorio_tipo">
                <select name="tipo" id="tipo" class="form-control font-roboto-12 select2">
                    <option value="">--Seleccionar--</option>
                    @foreach ($tipos as $index => $value)
                        <option value="{{ $index }}" @if(old('tipo') == $index) selected @endif >{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
