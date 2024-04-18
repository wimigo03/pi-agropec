<br>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-verdana-bg">
        <label for="primer_nombre" class="d-inline">Primer Nombre</label>
        <input type="text" value="{{ $personal->primer_nombre }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-4 pr-1 pl-1 font-verdana-bg">
        <label for="segundo_nombre" class="d-inline">Segundo Nombre</label>
        <input type="text" value="{{ $personal->segundo_nombre }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-4 pl-1 font-verdana-bg">
        <label for="foto" class="d-inline">Foto</label>
        <input type="file" name="foto" class="form-control font-verdana-bg" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-verdana-bg">
        <label for="ap_paterno" class="d-inline">Apellido Paterno</label>
        <input type="text" value="{{ $personal->apellido_paterno }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-4 pr-1 pl-1 font-verdana-bg">
        <label for="ap_materno" class="d-inline">Apellido Materno</label>
        <input type="text" value="{{ $personal->apellido_materno }}" class="form-control font-verdana-bg" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2 pr-1 font-verdana-bg">
        <label for="nacionalidad" class="d-inline">Nacionalidad</label>
        <input type="text" value="{{ $personal->nacionalidad }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
        <label for="ci_run" class="d-inline">Ci/Run</label>
        <input type="text" value="{{ $personal->ci_run }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
        <label for="extension" class="d-inline">Extension</label>
        <input type="text" value="{{ $personal->extension }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
        <label for="fecha_nac" class="d-inline">Fecha nac.</label>
        <input type="text" value="{{ \Carbon\Carbon::parse($personal->fecha_nac)->format('d/m/Y') }}" id="fecha_nac" class="form-control font-verdana-bg" readonly data-language="es" readonly>
    </div>
    <div class="col-md-1 pr-1 pl-1 font-verdana-bg">
        <label for="edad" class="d-inline">Edad</label>
        <input type="text" value="#" id="edad" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-3 pl-1 font-verdana-bg">
        <label for="lugar_nacimiento" class="d-inline">Lugar Nacimiento</label>
        <input type="text" value="{{ $personal->lugar_nacimiento }}" class="form-control font-verdana-bg" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2 pr-1 font-verdana-bg">
        <label for="sexo" class="d-inline">Sexo</label>
        <input type="text" value="{{ $personal->sexo }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
        <label for="licencia_conducir" class="d-inline">Licencia Conducir</label>
        <input type="text" value="{{ $personal->licencia_conducir }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-verdana-bg" id="form_licencia_categoria">
        <label for="licencia_categoria" class="d-inline">Categoria</label>
        <input type="text" value="{{ $personal->licencia_categoria }}" class="form-control font-verdana-bg" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2 pr-1 font-verdana-bg">
        <label for="celular" class="d-inline">Celular Personal</label>
        <input type="text" value="{{ $personal->celular }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
        <label for="telefono" class="d-inline">Telefono Fijo</label>
        <input type="text" value="{{ $personal->telefono }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-6 pr-1 pl-1 font-verdana-bg">
        <label for="domicilio" class="d-inline">Domicilio</label>
        <input type="text" value="{{ $personal->direccion_domicilio }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
        <label for="e_civil" class="d-inline">Estado Civil</label>
        <input type="text" value="{{ $personal->estado_civil }}" class="form-control font-verdana-bg" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4 pr-1 font-verdana-bg">
        <label for="empresa_id" class="d-inline">Empresa</label>
        <input type="text" value="{{ $personal->empresa->nombre_comercial }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-3 pr-1 pl-1 font-verdana-bg">
        <label for="cargo" class="d-inline">Cargo</label>
        <input type="text" value="{{ $personal->cargo->nombre }}" class="form-control font-verdana-bg" readonly>
    </div>
</div>