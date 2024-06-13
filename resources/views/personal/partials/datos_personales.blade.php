<br>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-roboto-12">
        <label for="primer_nombre" class="d-inline">Primer Nombre</label>
        <input type="text" name="primer_nombre" value="{{ old('primer_nombre') }}" id="primer_nombre" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
    <div class="col-md-4 pr-1 pl-1 font-roboto-12">
        <label for="segundo_nombre" class="d-inline">Segundo Nombre</label>
        <input type="text" name="segundo_nombre" value="{{ old('segundo_nombre') }}" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class="col-md-4 pl-1 font-roboto-12">
        <label for="foto" class="d-inline">Foto</label>
        <input type="file" name="foto" class="form-control font-roboto-12">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-roboto-12">
        <label for="ap_paterno" class="d-inline">Apellido Paterno</label>
        <input type="text" name="ap_paterno" value="{{ old('ap_paterno') }}" id="ap_paterno" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
    <div class="col-md-4 pr-1 pl-1 font-roboto-12">
        <label for="ap_materno" class="d-inline">Apellido Materno</label>
        <input type="text" name="ap_materno" value="{{ old('ap_materno') }}" id="ap_materno" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2 pr-1 font-roboto-12">
        <label for="nacionalidad" class="d-inline">Nacionalidad</label>
        <div class="select2-container--obligatorio" id="obligatorio_nacionalidad_id">
            <select name="nacionalidad" id="nacionalidad" class="form-control font-roboto-12 select2">
                <option value="">--Seleccionar--</option>
                @foreach ($nacionalidades as $index => $value)
                    <option value="{{ $index }}" @if(old('nacionalidad') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="ci_run" class="d-inline">Ci/Run</label>
        <input type="text" name="ci_run" value="{{ old('ci_run') }}" id="ci_run" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="extension" class="d-inline">Extension</label>
        <div class="select2-container--obligatorio" id="obligatorio_extension">
            <select name="extension" id="extension" class="form-control font-roboto-12 select2" onchange="verificarObligatorio();">
                <option value="">--Seleccionar--</option>
                @foreach ($extensiones as $index => $value)
                    <option value="{{ $index }}" @if(old('extension') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="fecha_nac" class="d-inline">Fecha nac.</label>
        <input type="text" name="fecha_nac" value="{{ old('fecha_nac') }}" id="fecha_nac" placeholder="dd/mm/aaaa" class="form-control font-roboto-12 obligatorio" data-language="es" onkeyup=countChars(this); oninput="verificarObligatorio();">
    </div>
    <div class="col-md-1 pr-1 pl-1 font-roboto-12">
        <label for="edad" class="d-inline">Edad</label>
        <input type="text" name="edad_nac" value="{{ old('edad_nac') }}" id="edad_nac" class="form-control font-roboto-12" readonly>
    </div>
    <div class="col-md-3 pl-1 font-roboto-12">
        <label for="lugar_nacimiento" class="d-inline">Lugar Nacimiento</label>
        <input type="text" name="lugar_nacimiento" value="{{ old('lugar_nacimiento') }}" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2 pr-1 font-roboto-12">
        <label for="sexo" class="d-inline">Sexo</label>
        <div class="select2-container--obligatorio" id="obligatorio_sexo">
            <select name="sexo" id="sexo" class="form-control font-roboto-12 select2 obligatorio" onchange="verificarObligatorio();">
                <option value="">--Seleccionar--</option>
                <option value="M" @if(old('sexo') == 'M') selected @endif >Masculino</option>
                <option value="F" @if(old('sexo') == 'F') selected @endif >Femenino</option>
            </select>
        </div>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="licencia_conducir" class="d-inline">Licencia Conducir</label>
        <div class="select2-container--obligatorio" id="obligatorio_licencia_conducir">
            <select name="licencia_conducir" id="licencia_conducir" class="form-control font-roboto-12 select2 obligatorio" onchange="verificarObligatorio();">
                <option value="">--Seleccionar--</option>
                <option value="SI" @if(old('licencia_conducir') == 'SI') selected @endif >SI</option>
                <option value="NO" @if(old('licencia_conducir') == 'NO') selected @endif >NO</option>
            </select>
        </div>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12" id="form_licencia_categoria">
        <label for="licencia_categoria" class="d-inline">Categoria</label>
        <select name="licencia_categoria" id="licencia_categoria" class="form-control font-roboto-12 select2">
            <option value="">--Seleccionar--</option>
            @foreach ($licencia_categorias as $index => $value)
                <option value="{{ $index }}" @if(old('licencia_categoria') == $index) selected @endif >{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2 pr-1 font-roboto-12">
        <label for="celular" class="d-inline">Celular Personal</label>
        <input type="text" name="celular" value="{{ old('celular') }}" id="celular" class="form-control font-roboto-12 obligatorio" onkeypress="return valideNumberSinDecimal(event);" oninput="verificarObligatorio();">
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="telefono" class="d-inline">Telefono Fijo</label>
        <input type="text" name="telefono" value="{{ old('telefono') }}" id="telefono" class="form-control font-roboto-12" onkeypress="return valideNumberSinDecimal(event);">
    </div>
    <div class="col-md-6 pr-1 pl-1 font-roboto-12">
        <label for="domicilio" class="d-inline">Domicilio</label>
        <input type="text" name="domicilio" value="{{ old('domicilio') }}" id="domicilio" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
    <div class="col-md-2 pl-1 font-roboto-12">
        <label for="e_civil" class="d-inline">Estado Civil</label>
        <div class="select2-container--obligatorio" id="obligatorio_estado_civil">
            <select name="estado_civil" id="estado_civil" class="form-control font-roboto-12 select2 obligatorio" onchange="verificarObligatorio();">
                <option value="">--Seleccionar--</option>
                <option value="SOLTERO(A)" @if(old('estado_civil') == 'SOLTERO(A)') selected @endif>SOLTERO(A)</option>
                <option value="CONCUBINO(A)" @if(old('estado_civil') == 'CONCUBINO(A)') selected @endif>CONCUBINO(A)</option>
                <option value="CASADO(A)" @if(old('estado_civil') == 'CASADO(A)') selected @endif>CASADO(A)</option>
                <option value="DIVORCIADO(A)" @if(old('estado_civil') == 'DIVORCIADO(A)') selected @endif>DIVORCIADO(A)</option>
                <option value="VIUDO(A)" @if(old('estado_civil') == 'VIUDO(A)') selected @endif>VIUDO(A)</option>
            </select>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-3 pr-1 font-roboto-12">
        <label for="empresa_id" class="d-inline">Empresa</label>
        <input type="text" value="{{ $empresa->nombre_comercial }}" class="form-control font-roboto-12" disabled>
        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_input_id">
    </div>
    <div class="col-md-3 pr-1 pl-1 font-roboto-12">
        <label for="cargo" class="d-inline">Cargo</label>
        <div class="select2-container--obligatorio" id="obligatorio_cargo_id">
            <select name="cargo_id" id="cargo_id" class="form-control select2" onchange="verificarObligatorio();">
                <option value="">-</option>
                @foreach ($cargos as $index => $value)
                    <option value="{{ $index }}" @if(old('cargo_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
