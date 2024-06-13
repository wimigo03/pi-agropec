<form action="#" method="get" id="form">

</form>
<div class="form-group row">
    <div class="col-md-12 px-1">
        @can('balance.apertura.index')
            <span class="tts:right tts-slideIn tts-custom" aria-label="Cambiar" style="cursor: pointer;">
                <span class="btn btn-outline-secondary font-roboto-12" onclick="cambiari();">
                    <i class="fa-solid fa-file-invoice-dollar fa-fw"></i>
                </span>
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
