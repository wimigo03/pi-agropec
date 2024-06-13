<form action="#" method="get" id="form">

</form>
<div class="form-group row">
    <div class="col-md-12 px-1">
        @can('balance.apertura.f.index')
            <span class="tts:right tts-slideIn tts-custom" aria-label="Cambiar" style="cursor: pointer;">
                <button class="btn btn-outline-warning font-roboto-12" type="button" onclick="cambiarf();">
                    <i class="fa-solid fa-file-invoice-dollar fa-fw"></i>
                </button>
            </span>
        @endcan
        @can('balance.apertura.create')
            <span class="tts:right tts-slideIn tts-custom" aria-label="Crear" style="cursor: pointer;">
                <button class="btn btn-outline-success font-roboto-12" type="button" onclick="create();">
                    <i class="fas fa-plus fa-fw"></i>
                </button>
            </span>
        @endcan
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
    {{--<div class="col-md-6 px-0 pl-1 text-right">
        <button class="btn btn-outline-primary font-verdana" type="button" onclick="search();">
            &nbsp;<i class="fas fa-search"></i>&nbsp;Buscar
        </button>
        <button class="btn btn-outline-danger font-verdana" type="button" onclick="limpiar();">
            &nbsp;<i class="fas fa-eraser"></i>&nbsp;Limpiar
        </button>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>--}}
</div>
