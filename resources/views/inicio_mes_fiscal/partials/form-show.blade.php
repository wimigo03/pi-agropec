<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-0 pr-1">
            <label for="gestion" class="d-inline">Gestion de arranque</label>
            <br>
            <span>¿Cual sera el primer año que empezara a hacer su primer registro con Pi-Conta?</span>
            <input type="text" value="{{ $inicio_mes_fiscal->inicio_gestion }}" id="anho" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-0 pr-1">
            <label for="mes_ejercicio_fiscal" class="d-inline">Mes Ejercicio Fiscal</label>
            <input type="text" value="{{ $inicio_mes_fiscal->mes }}" id="mes" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-5 px-0 pr-1">
            <label for="pregunta_1" class="d-inline">Otros</label>
            <br>
            <span>¿Empezará su registro con datos traidos desde otro sistema?</span>
            <input type="text" value="{{ $inicio_mes_fiscal->sistema_otro }}" id="otro_sistema" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    @if ($inicio_mes_fiscal == '2')
        <div class="form-group row font-roboto-12 abs-center" id="fecha_otro_sistema">
            <div class="col-md-5 px-0 pr-1">
                <span>Por favor indicar la fecha de inicio</span>
                <input type="text" name="fecha" value="{{ $inicio_mes_fiscal->fecha_otro_sistema }}" id="fecha" placeholder="dd/mm/aaaa" class="form-control font-roboto-12" data-language="es" disabled>
            </div>
        </div>
    @endif
    <div class="form-group row abs-center">
        <div class="col-md-5 pr-1 pl-1 text-center">
            <button class="btn btn-outline-primary font-verdana" type="button" onclick="cancelar();">
                &nbsp;<i class="fas fa-arrow-left"></i>&nbsp;Volver
            </button>
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
</form>
