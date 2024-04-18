<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-3 px-0 pr-1">
            <label for="empresa" class="d-inline">Empresa</label>
            <input type="text" value="{{ $empresa->nombre_comercial }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-2 px-0 pl-1">
            <label for="anho" class="d-inline">Gestion</label>
            <select name="anho" id="anho" class="form-control select2">
                @foreach ($anhos as $index => $value)
                    <option value="{{ $index }}" @if(request('anho') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>
<div class="form-group row abs-center">
    <div class="col-md-5 text-center">
        <button class="btn btn-outline-primary font-verdana" type="button" onclick="procesar();">
            <i class="fas fa-paper-plane"></i>&nbsp;Procesar
        </button>
        <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
            &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
        </button>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>