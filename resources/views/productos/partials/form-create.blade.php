<form action="#" method="post" id="form" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-md-4 px-1 pr-1 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
            <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()" disabled>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <label for="categoria_master_id" class="d-inline">Categoria Master</label>
            <div class="select2-container--obligatorio" id="obligatorio_categoria_master_id">
                <select name="categoria_master_id" id="categoria_master_id" class="form-control font-roboto-12 select2" onchange="verificarObligatorio();">
                    <option value="">--Seleccionar--</option>
                    @foreach ($categorias_master as $index => $value)
                        <option value="{{ $index }}" @if(old('categoria_master_id') == $index) selected @endif >{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4 px-1 pl-1 font-roboto-12">
            <label for="categoria" class="d-inline">Categoria</label>
            <div class="select2-container--obligatorio" id="obligatorio_categoria_id">
                <select id="categoria_id" name="categoria_id" class="form-control font-roboto-12 select2" onchange="verificarObligatorio();">
                    <option value="">--Seleccionar--</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4 px-1 pr-1 font-roboto-12">
            <label for="nombre" class="d-inline">Nombre Comercial</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <label for="nombre_factura" class="d-inline">Nombre Factura</label>
            <input type="text" name="nombre_factura" value="{{ old('nombre_factura') }}" id="nombre_factura" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="codigo" class="d-inline">Codigo</label>
            <input type="text" name="codigo" value="{{ old('codigo') }}" id="codigo" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();" readonly>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="unidad" class="d-inline">Unidad</label>
            <div class="select2-container--obligatorio" id="obligatorio_unidad_id">
                <select name="unidad_id" id="unidad_id" class="form-control font-roboto-12 select2" onchange="verificarObligatorio();">
                    <option value="">--Seleccionar--</option>
                    @foreach ($unidades as $index => $value)
                        <option value="{{ $index }}" @if(old('unidad_id') == $index) selected @endif >{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-1 pr-1 pl-1 font-roboto-12">
            <br>
            <span class="tts:right tts-slideIn tts-custom" aria-label="Crear Unidad" style="cursor: pointer;">
                <span class="btns btn btn-warning font-verdana" data-toggle="modal" data-target="#ModalUnidadMedida">
                    <i class="fa-solid fa-scale-balanced fa-fw"></i>
                </span>
            </span>
        </div>
        <div class="col-md-8 px-1 pl-1 font-roboto-12">
            <label for="detalle" class="d-inline">Descripcion</label>
            <input type="text" name="detalle" value="{{ old('detalle') }}" id="detalle" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4 px-1 pr-1 font-roboto-12">
            <label for="file_1" class="d-inline">Imagen Principal</label>
            <input type="file" name="foto_1" class="form-control font-roboto-12">
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <label for="file" class="d-inline">Imagen Alternativa(1)</label>
            <input type="file" name="foto_2" class="form-control font-roboto-12">
        </div>
        <div class="col-md-4 px-1 pl-1 font-roboto-12">
            <label for="file" class="d-inline">Imagen Alternativa(2)</label>
            <input type="file" name="foto_3" class="form-control font-roboto-12">
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1 text-right">
        <span class="btn btn-outline-primary font-roboto-12" onclick="procesar();">
            <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Procesar
        </span>
        <span class="btn btn-outline-danger font-roboto-12" onclick="cancelar();">
            &nbsp;<i class="fas fa-times fa-fw"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
