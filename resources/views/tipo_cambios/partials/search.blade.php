<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
    <div class="form-group row">
        <div class="col-md-2 px-0 pr-1">
            <input type="text" name="fecha_i" value="{{ old('fecha_i') }}" id="fecha_i" placeholder="(desde) - dd/mm/aaaa" class="form-control font-roboto-12 obligatorio" data-language="es" onkeyup=countCharsI(this);>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="fecha_f" value="{{ old('fecha_f') }}" id="fecha_f" placeholder="(hasta) - dd/mm/aaaa" class="form-control font-roboto-12 obligatorio" data-language="es" onkeyup=countCharsF(this);>
        </div>
    </div>
</form>