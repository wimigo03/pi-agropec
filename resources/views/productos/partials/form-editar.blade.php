<form action="#" method="post" id="form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="producto_id" value="{{ $producto->id }}" id="producto_id">
    <input type="hidden" value="{{ $producto->categoria_id }}" id="producto_categoria_id">
    <div class="form-group row">
        <div class="col-md-4 px-1 pr-1 font-roboto-12">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
            <input type="text" value="{{ $empresa->nombre_comercial }}" id="empresa" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()" disabled>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <label for="nombre" class="d-inline">Categoria Master</label>
            <div class="select2-container--obligatorio" id="obligatorio_categoria_master_id">
                <select name="categoria_master_id" id="categoria_master_id" placeholder="--Seleccionar--" class="form-control select2" onchange="verificarObligatorio();">
                    @foreach ($categorias_master as $categoria_master)
                        <option value="{{ $categoria_master->id }}"
                            @if($categoria_master->id == old('categoria_master_id') || (isset($producto) && $producto->categoria_master_id == $categoria_master->id))
                                selected
                            @endif>
                            {{ $categoria_master->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4 px-1 pl-1 font-roboto-12">
            <label for="categoria" class="d-inline">Categoria</label>
            <div class="select2-container--obligatorio" id="obligatorio_categoria_id">
                <select name="categoria_id" id="categoria_id" placeholder="--Seleccionar--" class="form-control select2" onchange="verificarObligatorio();">
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}"
                            @if($categoria->id == old('categoria_id') || (isset($producto) && $producto->categoria_id == $categoria->id))
                                selected
                            @endif>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4 px-1 pr-1 font-roboto-12">
            <label for="nombre" class="d-inline">Nombre Comercial</label>
            <input type="text" name="nombre" value="{{ $producto->nombre }}" id="nombre" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <label for="nombre_factura" class="d-inline">Nombre Factura</label>
            <input type="text" name="nombre_factura" value="{{ $producto->nombre_factura }}" id="nombre_factura" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
        </div>
        <div class="col-md-2 px-1 pl-1 font-roboto-12">
            <label for="codigo" class="d-inline">Codigo</label>
            <input type="text" name="codigo" value="{{ $producto->codigo }}" id="codigo" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();" readonly>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="unidad" class="d-inline">Unidad</label>
            <div class="select2-container--obligatorio" id="obligatorio_unidad_id">
                <select name="unidad_id" id="unidad_id" placeholder="--Seleccionar--" class="form-control select2" onchange="verificarObligatorio();">
                    @foreach ($unidades as $unidad)
                        <option value="{{ $unidad->id }}"
                            @if($unidad->id == request('unidad_id') || (isset($producto) && $producto->unidad_id == $unidad->id))
                                selected
                            @endif>
                            {{ $unidad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-9 px-1 pl-1 font-roboto-12">
            <label for="detalle" class="d-inline">Descripcion</label>
            <input type="text" name="detalle" value="{{ $producto->detalle }}" id="detalle" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4 px-1 pr-1 font-roboto-12">
            <label for="foto_1" class="#">Modificar Imagen Principal</label>
            <input type="file" name="foto_1" class="form-control font-roboto-12">
        </div>
        <div class="col-md-8 px-1 pl-1 font-roboto-12 text-center">
            <label for="foto_1" class="#">Imagen Actual Principal</label><br>
            <img src="{{ $producto->foto_1 != null ? url($producto->foto_1) : '#' }}" alt="{{ $producto->foto_1 != null ? $producto->foto_1 : '#'}}" style="width: 200px; height:auto;">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4 px-1 pr-1 font-roboto-12">
            <label for="foto_2" class="#">Modificar Imagen Alternativa (1)</label>
            <input type="file" name="foto_2" class="form-control font-roboto-12">
        </div>
        <div class="col-md-8 px-1 pl-1 font-roboto-12 text-center">
            <label for="foto_2" class="#">Imagen Actual (1)</label><br>
            <img src="{{ $producto->foto_2 != null ? url($producto->foto_2) : '#' }}" alt="{{ $producto->foto_2 != null ?  $producto->foto_2 : '#' }}" style="width: 200px; height:auto;">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4 px-1 pr-1 font-roboto-12">
            <label for="foto_3" class="#">Modificar Imagen Alternativa (2)</label>
            <input type="file" name="foto_3" class="form-control font-roboto-12">
        </div>
        <div class="col-md-8 px-1 pl-1 font-roboto-12 text-center">
            <label for="foto_3" class="#">Imagen Actual (2)</label><br>
            <img src="{{ $producto->foto_3 != null ? url($producto->foto_3) : '#' }}" alt="{{ $producto->foto_3 != null ? $producto->foto_3 : '#' }}" style="width: 200px; height:auto;">
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 text-right">
        <span class="btn btn-outline-primary font-roboto-12" onclick="procesar();">
            <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Procesar
        </span>
        <span class="btn btn-outline-danger font-roboto-12" onclick="cancelar();">
            <i class="fas fa-times fa-fw"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
