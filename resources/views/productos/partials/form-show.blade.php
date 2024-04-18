<input type="hidden" value="{{ $producto->empresa_id }}" id="empresa_id">
<input type="hidden" value="{{ $producto->id }}" id="producto_id">
<div class="form-group row">
    <div class="col-md-12 pr-1 font-verdana">
        <label for="#" class="d-inline">EMPRESA.- </label>
        {{ $producto->empresa->nombre_comercial }}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4 px-0 font-verdana">
        <label for="#" class="d-inline">CATEGORIA MASTER.- </label>
        {{ $producto->categoria_master }}
    </div>
    <div class="col-md-4 pr-1 pl-1 font-verdana">
        <label for="#" class="d-inline">CATEGORIA.- </label>
        {{ $producto->categoria != null ? $producto->categoria->nombre : '-' }}
    </div>
    <div class="col-md-4 px-0 font-verdana">
        <label for="#" class="d-inline">PLAN DE CUENTA.- </label>
        {{ $producto->plan_cuenta != null ? $producto->plan_cuenta->nombre : '#' }}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6 px-0 font-verdana">
        <label for="#" class="d-inline">NOMBRE.- </label>
        {{ $producto->nombre }}
    </div>
    <div class="col-md-6 px-0 font-verdana">
        <label for="#" class="d-inline">NOMBRE EN LA FACTURA.- </label>
        {{ $producto->nombre_factura }}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4 px-0 font-verdana">
        <label for="#" class="d-inline">UNIDAD DE MEDIDA.- </label>
        {{ $producto->unidad->nombre }}
    </div>
    <div class="col-md-4 pr-1 pl-1 font-verdana">
        <label for="#" class="d-inline">CODIGO.- </label>
        {{ $producto->codigo }}
    </div>
    <div class="col-md-4 px-0 font-verdana">
        <label for="#" class="d-inline">ESTADO.- </label>
        {{ $producto->status }}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-12 pr-1 font-verdana">
        <label for="#" class="d-inline">DETALLE.- </label>
        {{ $producto->detalle }}
    </div>
</div>
<div class="form-group row">
    @if ($producto->foto_1 != null)
    <div class="col-md-4 px-0 font-verdana text-center">
        <a href="{{ asset($producto->foto_1) }}" target="_blank">
            <img src="{{ url($producto->foto_1) }}" alt="{{ $producto->foto_1 }}" style="width: 200px; height:auto;">
        </a>
    </div>
    @endif
    @if ($producto->foto_2 != null)
        <div class="col-md-4 pr-1 pl-1 font-verdana text-center">
            <a href="{{ asset($producto->foto_2) }}" target="_blank">
                <img src="{{ url($producto->foto_2) }}" alt="{{ $producto->foto_2 }}" style="width: 200px; height:auto;">
            </a>
        </div>
    @endif
    @if ($producto->foto_3 != null)
        <div class="col-md-4 px-0 font-verdana text-center">
            <a href="{{ asset($producto->foto_3) }}" target="_blank">
                <img src="{{ url($producto->foto_3) }}" alt="{{ $producto->foto_3 }}" style="width: 200px; height:auto;">
            </a>
        </div>
    @endif
</div>
<div class="form-group row">
    <div class="col-md-12 text-right">
        <button class="btn btn-primary font-verdana" type="button" onclick="index();">
            &nbsp;<i class="fas fa-chevron-left"></i>&nbsp;
        </button>
        @can('productos.pdf')
            <button class="btn btn-danger font-verdana" type="button" onclick="pdf();">
                &nbsp;<i class="fas fa-file-pdf"></i>&nbsp;
            </button>
        @endcan
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>