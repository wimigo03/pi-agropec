<form action="#" method="get" id="form">
    <div class="form-group row font-roboto-12 abs-center">
        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
        <div class="col-md-1 px-0 pr-1">
            @can('libro.mayor.auxiliar.general.f.index')
                <br>
                <span class="tts:right tts-slideIn tts-custom" aria-label="Cambiar" style="cursor: pointer;">
                    <button class="btn btn-outline-warning font-roboto-12" type="button" onclick="cambiarf();">
                        <i class="fa-solid fa-file-invoice-dollar fa-fw"></i>
                    </button>
                </span>
            @endcan
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <label for="fecha_i" class="d-inline">Fecha Inicial</label>
            <input type="text" name="fecha_i" value="{{ request('fecha_i') }}" id="fecha_i" placeholder="dd/mm/aaaa" class="form-control font-roboto-12" data-language="es">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <label for="fecha_f" class="d-inline">Fecha Final</label>
            <input type="text" name="fecha_f" value="{{ request('fecha_f') }}" id="fecha_f" placeholder="dd/mm/aaaa" class="form-control font-roboto-12" data-language="es">
        </div>
        <div class="col-md-5 pr-1 pl-1">
            <label for="plan_cuenta_auxiliar_id" class="d-inline">Auxiliar</label>
            <select name="plan_cuenta_auxiliar_id" id="plan_cuenta_auxiliar_id" class="form-control select2">
                @foreach ($auxiliares as $index => $value)
                <option value="">-</option>
                    <option value="{{ $index }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 px-0 pl-1">
            <label for="estado" class="d-inline">Estados</label>
            <select name="estado" id="estado" class="form-control">
                <option value="_TODOS_">TODOS</option>
                @foreach ($estados_comprobantes as $index => $value)
                    <option value="{{ $index }}" @if(old('estado') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-12 px-0 pl-1 text-right">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                &nbsp;<i class="fas fa-search fa-fw"></i>&nbsp;Procesar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
                &nbsp;<i class="fas fa-eraser fa-fw"></i>&nbsp;Limpiar
            </button>
            <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
</form>
