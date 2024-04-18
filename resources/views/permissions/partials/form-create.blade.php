<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-4 px-0 pr-1">
            <label for="modulo" class="d-inline">Modulo</label>
            <select name="modulo_id" id="modulo_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($modulos as $index => $value)
                    <option value="{{ $index }}" @if(old('modulo_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-4 px-0 pr-1">
            <label for="titulo" class="d-inline">Titulo</label>
            <select name="titulo" id="titulo" placeholder="--Seleccionar--" class="form-control select2">
                <option value="">-</option>
                <option value="_NUEVO_">PRIMER TITULO</option>
                @foreach ($titulos as $titulo)
                    <option value="{{ $titulo->title }}"
                        @if($titulo->title == old('titulo'))
                            selected
                        @endif>
                        {{ strtoupper($titulo->title) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row abs-center font-roboto-12" id="form_nuevo_titulo">
        <div class="col-md-4 px-0 pr-1">
            <label for="nuevo_titulo" class="d-inline">Nuevo Primer Titulo</label>
            <input type="text" name="nuevo_titulo" value="{{ old('nuevo_titulo') }}" id="nuevo_titulo" class="form-control font-roboto-12">
        </div>
    </div>
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-4 px-0 pr-1">
            <label for="nombre" class="d-inline">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control font-roboto-12" oninput="this.value = this.value.toLowerCase();">
        </div>
    </div>
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-4 px-0 pr-1">
            <label for="descripcion" class="d-inline">Descripcion</label>
            <input type="text" name="descripcion" value="{{ old('descripcion') }}" id="descripcion" class="form-control font-verdana">
        </div>
    </div>
</form>
