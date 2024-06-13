<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
    <div class="form-group row font-roboto-12">
        <div class="col-md-2 px-1 pr-1">
            <input type="text" name="codigo_ingreso" placeholder="--Codigo Ingreso--" value="{{ request('codigo_ingreso') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="codigo_retiro" placeholder="--Codigo Retiro--" value="{{ request('codigo_retiro') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <input type="text" name="ci_run" placeholder="--Ci/Run--" value="{{ request('ci_run') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <input type="text" name="primer_nombre" placeholder="--Primer Nombre--" value="{{ request('primer_nombre') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-3 px-1 pl-1">
            <input type="text" name="apellido_paterno" placeholder="--Apellido Paterno--" value="{{ request('apellido_paterno') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-3 px-1 pr-1">
            <input type="text" name="apellido_materno" placeholder="--Apellido Materno--" value="{{ request('apellido_materno') }}" class="form-control font-roboto-12 intro" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-5 pr-1 pl-1">
            <select name="cargo_id" id="cargo_id" class="form-control">
                <option value="">-</option>
                @foreach ($cargos as $index => $value)
                    <option value="{{ $index }}" @if(request('cargo_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <select name="file_contrato" id="file_contrato" class="form-control">
                <option value="">-</option>
                <option value="1" @if(request('file_contrato') == '1') selected @endif >CON CONTRATO</option>
                <option value="2" @if(request('file_contrato') == '2') selected @endif >SIN CONTRATO</option>
            </select>
        </div>
        <div class="col-md-2 px-1 pl-1">
            <select name="estado" id="estado" class="form-control">
                <option value="">-</option>
                @foreach ($estados as $index => $value)
                    <option value="{{ $index }}" @if(request('estado') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 px-1">
        @can('personal.create')
            <span class="btn btn-outline-success font-roboto-12" onclick="create();">
                <i class="fas fa-plus fa-fw"></i>
            </span>
        @endcan
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        <span class="btn btn-outline-danger font-roboto-12 float-right" onclick="limpiar();">
            <i class="fas fa-eraser fa-fw"></i>&nbsp;Limpiar
        </span>
        <span class="btn btn-outline-primary font-roboto-12 float-right mr-1" onclick="search();">
            <i class="fas fa-search fa-fw"></i>&nbsp;Buscar
        </span>
    </div>
</div>
