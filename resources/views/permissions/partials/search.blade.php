<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
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
        <div class="col-md-4 pr-1 pl-1">
            <input type="text" name="nombre" placeholder="--Nombre--" value="{{ request('nombre') }}" class="form-control font-roboto-12 intro">
        </div>
    </div>
</form>
