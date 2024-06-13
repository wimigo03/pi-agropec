<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <div class="form-group row">
        <div class="col-md-4 px-1 pr-1 font-roboto-12">
            <label for="pi_cliente_id" class="d-inline">Cliente</label>
            <input type="hidden" name="pi_cliente_id" value="{{ $user->pi_cliente_id }}">
            <input type="text" value="{{ $user->cliente != null ? $user->cliente->razon_social : '#' }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="empresa_id" class="d-inline">Empresa</label>
            <input type="hidden" name="empresa_id" value="{{ $user->empresa_id }}" id="empresa_id">
            <input type="text" value="{{ $user->empresa != null ? $user->empresa->nombre_comercial : '#' }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-5 px-1 pl-1 font-roboto-12">
            <label for="cargo_id" class="d-inline">Cargo</label>
            <input type="hidden" name="cargo_id" value="{{ $user->cargo_id }}">
            <input type="text" value="{{ $user->cargo != null ? $user->cargo->nombre : '#' }}" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-5 px-1 pr-1 font-roboto-12">
            <label for="nombre" class="d-inline">Nombre</label>
            <input type="text" name="nombre" value="{{ $user->name }}" class="form-control font-roboto-12">
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="username" class="d-inline">Usuario</label>
            <input type="text" value="{{ $user->username }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-12">
            <label for="email" class="d-inline">Correo Electronico</label>
            <input type="text" name="email" value="{{ $user->email }}" class="form-control font-roboto-12">
        </div>
        <div class="col-md-2 px-1 pl-1 font-roboto-12">
            <label for="estado" class="d-inline">Estado</label>
            <input type="text" value="{{ $user->status }}" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-1 pr-1 font-roboto-12">
            <label for="password" class="d-inline">Clave de Acceso</label>
            <input type="password" name="password" class="form-control font-verdana-bg">
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 text-right">
        <button class="btn btn-outline-primary font-roboto-12" type="button" onclick="procesar();">
            <i class="fas fa-paper-plane"></i>&nbsp;Actualizar
        </button>
        <button class="btn btn-outline-danger font-roboto-12" type="button" onclick="cancelar();">
            &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
        </button>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
