<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" id="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row">
        <div class="col-md-4 px-0 pr-1 font-roboto-12">
            <select name="modulo_id" id="modulo_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($modulos as $index => $value)
                    <option value="{{ $index }}" @if(request('modulo_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <select name="titulo" id="titulo" placeholder="--Seleccionar--" class="form-control select2">
                <option value="">-</option>
                @foreach ($titulos as $titulo)
                    <option value="{{ $titulo->title }}"
                        @if($titulo->title == request('titulo'))
                            selected
                        @endif>
                        {{ strtoupper($titulo->title) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 px-0 pl-1">
            <input type="text" name="nombre" placeholder="--Nombre--" value="{{ request('nombre') }}" class="form-control font-roboto-12 intro">
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12">
        @can('permissions.create')
            <span class="btn btn-outline-success font-roboto-12" onclick="create();">
                <i class="fas fa-plus fa-fw"></i>
            </span>
        @endcan
        <span class="btn btn-outline-danger font-roboto-12 float-right" onclick="limpiar();">
            <i class="fas fa-eraser fa-fw"></i>&nbsp;Limpiar
        </span>
        <span class="btn btn-outline-primary font-roboto-12 mr-1 float-right" onclick="search();">
            <i class="fas fa-search fa-fw"></i>&nbsp;Buscar
        </span>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
