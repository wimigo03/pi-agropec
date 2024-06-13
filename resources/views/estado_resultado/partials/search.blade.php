<form action="#" method="get" id="form">
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-1 px-1 pr-1">
            @can('estado.resultado.f.index')
                <br>
                <span class="tts:right tts-slideIn tts-custom" aria-label="Cambiar" style="cursor: pointer;">
                    <button class="btn btn-outline-secondary font-roboto-12" type="button" onclick="cambiarf();">
                        <i class="fa-solid fa-file-invoice-dollar fa-fw"></i>
                    </button>
                </span>
            @endcan
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <label for="empresa" class="d-inline">Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($empresas as $index => $value)
                    <option value="{{ $index }}" @if(request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <label for="fecha_i" class="d-inline">Fecha Inicial</label>
            <input type="text" name="fecha_i" value="{{ request('fecha_i') }}" id="fecha_i" placeholder="(desde) - dd-mm-aaaa" class="form-control font-roboto-12" data-language="es">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <label for="fecha_f" class="d-inline">Fecha Final</label>
            <input type="text" name="fecha_f" value="{{ request('fecha_f') }}" id="fecha_f" placeholder="(hasta) - dd-mm-aaaa" class="form-control font-roboto-12" data-language="es">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <label for="estado" class="d-inline">Estado</label>
            <select name="estado" id="estado" class="form-control">
                <option value='_todos_' @if(request('estado') == '_todos_') selected @endif>TODOS</option>
                <option value='2' @if(request('estado') == '2') selected @endif>SOLO APROBADOS</option>
                <option value="1" @if(request('estado') == '1') selected @endif>SOLO PENDIENTES</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12 px-1">
            @if ($show == '1')
                <span class="tts:right tts-slideIn tts-custom" aria-label="Exportar a Excel" style="cursor: pointer;">
                    <span class="btn btn-success font-roboto-12" onclick="excel();">
                        <i class="fa-solid fa-file-excel fa-fw"></i>
                    </span>
                </span>
                <span class="tts:right tts-slideIn tts-custom" aria-label="Exportar a Pdf" style="cursor: pointer;">
                    <span class="btn btn-danger font-roboto-12" onclick="pdf();">
                        <i class="fa-solid fa-file-pdf fa-fw"></i>
                    </span>
                </span>
                <i class="fa fa-spinner fa-spin fa-lg spinner-btn-send" style="display: none;"></i>
            @endif
            <span class="btn btn-outline-danger font-roboto-12 float-right" onclick="limpiar();">
                <i class="fas fa-eraser fa-fw"></i>&nbsp;Limpiar
            </span>
            <span class="btn btn-outline-primary font-roboto-12 float-right mr-1" onclick="procesar();">
                <i class="fas fa-search fa-fw"></i>&nbsp;Procesar
            </span>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
</form>
