<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="permission_id" value="{{ $permission->id }}">
    <input type="hidden" name="empresa_id" id="empresa_id" value="{{ $permission->empresa_id }}">
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-4 px-0 pr-1">
            <label for="modulo" class="d-inline">Modulo</label>
            <select name="modulo_id" id="modulo_id" class="form-control select2">
                @foreach ($modulos as $modulo)
                    <option value="{{ $modulo->id }}"
                        @if($modulo->id == old('modulo_id') || (isset($permission) && $permission->modulo_id == $modulo->id))
                            selected
                        @endif>
                        {{ $modulo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-4 px-0 pr-1">
            <label for="titulo" class="d-inline">Titulo</label>
            <select name="titulo" id="titulo" class="form-control select2">
                @foreach ($titulos as $titulo)
                    <option value="{{ $titulo->title }}"
                        @if($titulo->title == old('titulo') || (isset($permission) && $permission->title == $titulo->title))
                            selected
                        @endif>
                        {{ $titulo->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-4 px-0 pr-1">
            <label for="nombre" class="d-inline">Nombre</label>
            <input type="text" name="nombre" value="{{ $permission->name }}" id="nombre" class="form-control font-roboto-12" oninput="this.value = this.value.toLowerCase();">
        </div>
    </div>
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-4 px-0 pr-1">
            <label for="descripcion" class="d-inline">Descripcion</label>
            <input type="text" name="descripcion" value="{{ $permission->description }}" id="descripcion" class="form-control font-verdana">
        </div>
    </div>
</form>
<div class="form-group row abs-center font-roboto-12">
    <div class="col-md-2 px-1 pr-1">
        <span class="btn btn-block btn-outline-primary font-roboto-12" onclick="procesar();">
            <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Actualizar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
    <div class="col-md-2 pr-1 pl-1">
        <span class="btn btn-block btn-outline-danger font-roboto-12" onclick="cancelar();">
            <i class="fas fa-times fa-fw"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
