<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="plan_cuenta_auxiliar_id" value="{{ $plan_cuenta_auxiliar->id }}">
    <div class="form-group row abs-center">
        <div class="col-md-4 font-roboto-12">
            <label for="nombre" class="d-inline">Nombre Auxiliar</label>
            <input type="text" name="nombre" value="{{ $plan_cuenta_auxiliar->nombre }}" id="nombre" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase();">
        </div>
    </div>
</form>
