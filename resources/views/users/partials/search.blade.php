<form action="#" method="get" id="form">
    <div class="form-group row font-roboto-12">
        <div class="col-md-3 px-1 pr-1">
            <select name="empresa_id" id="empresa_id" class="form-control">
                <option value="">-</option>
                @foreach ($empresas as $index => $value)
                    <option value="{{ $index }}" @if(request('empresa_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <select name="cargo_id" id="cargo_id" class="form-control">
                <option value="">-</option>
                @foreach ($cargos as $index => $value)
                    <option value="{{ $index }}" @if(request('cargo_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <select name="role_id" id="role_id" class="form-control">
                <option value="">-</option>
                @foreach ($roles as $index => $value)
                    <option value="{{ $index }}" @if(request('role_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            <input type="text" name="nombre" placeholder="--Nombre--" value="{{ request('nombre') }}" class="form-control font-roboto-12 intro">
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-3 px-1 pr-1">
            <input type="text" name="username" placeholder="--Usuario--" value="{{ request('username') }}" class="form-control font-roboto-12 intro">
        </div>
        <div class="col-md-4 pr-1 pl-1">
            <input type="text" name="email" placeholder="--Correo Electronico--" value="{{ request('email') }}" class="form-control font-roboto-12 intro">
        </div>
        <div class="col-md-2 pr-1 pl-1">
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
    <div class="col-md-12 px-0 pl-1 text-right">
        <button class="btn btn-outline-primary font-roboto-12" type="button" onclick="search();">
            &nbsp;<i class="fas fa-search"></i>&nbsp;Buscar
        </button>
        <button class="btn btn-outline-danger font-roboto-12" type="button" onclick="limpiar();">
            &nbsp;<i class="fas fa-eraser"></i>&nbsp;Limpiar
        </button>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
