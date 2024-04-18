<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="configuracion_id" value="{{ $configuracion->id }}">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-0 pr-1">
            <label for="gestion" class="d-inline">Gestion de arranque</label>
            <br>
            <span>¿Cual sera el primer año que empezara a hacer su primer registro con Pi-Conta?</span>
            <input type="text" name="anho" value="{{ old('anho') }}" id="anho" class="form-control font-roboto-12">
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-0 pr-1">
            <label for="mes_ejercicio_fiscal" class="d-inline">Mes Ejercicio Fiscal</label>
            <select name="mes" id="mes" class="form-control font-roboto-12 select2">
                <option value="">--Seleccionar--</option>
                @foreach ($meses as $index => $value)
                    <option value="{{ $index }}" @if(old('mes') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-0 pr-1">
            <label for="pregunta_1" class="d-inline">Otros</label>
            <br>
            <span>¿Empezará su registro con datos traidos desde otro sistema?</span>
            <select name="pregunta_1" id="pregunta_1" class="form-control font-roboto-12 select2">
                <option value="">--Seleccionar--</option>
                <option value="1" @if(old('pregunta_1') == '1') selected @endif >Si</option>
                <option value="2" @if(old('pregunta_1') == '2') selected @endif >No</option>
            </select>
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center" id="fecha_otro_sistema">
        <div class="col-md-5 px-0 pr-1">
            <span>Por favor indicar la fecha de inicio</span>
            <input type="text" name="fecha" value="{{ old('fecha') }}" id="fecha" placeholder="dd/mm/aaaa" class="form-control font-roboto-12" data-language="es">
        </div>
    </div>
    <div class="form-group row abs-center">
        <div class="col-md-5 pr-1 pl-1 text-center">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
                <i class="fas fa-paper-plane"></i>&nbsp;Procesar
            </button>
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
                &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
            </button>
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
</form>
