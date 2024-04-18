<div class="form-group row font-roboto-12" id="subMenuProductos">
    @can('categorias.index')
        <div class="col-md-3 sub-botones-producto">
            <a href="{{ route('categorias.index',['empresa_id' => $empresa->id, 'status_platos' => '[]', 'status_insumos' => '[]']) }}" class="btn btn-outline-secondary btn-block font-roboto-12">
                <i class="fas fa-poll-h fa-fw"></i>&nbsp;Categorias
            </a>
        </div>
    @endcan
    @can('unidades.index')
        <div class="col-md-2 sub-botones-producto">
            <a href="{{ route('unidades.index',['empresa_id' => $empresa->id]) }}" class="btn btn-outline-secondary btn-block font-roboto-12">
                <i class="fas fa-balance-scale fa-fw"></i>&nbsp;Unidades
            </a>
        </div>
    @endcan
    @can('productos.index')
        <div class="col-md-3 sub-botones-producto">
            <a href="{{ route('productos.index',['empresa_id' => $empresa->id]) }}" class="btn btn-info btn-block font-roboto-12">
                <i class="fas fa-wine-glass-alt fa-fw fa-beat"></i>&nbsp;Productos
            </a>
        </div>
    @endcan
    @can('tipo.precios.index')
        <div class="col-md-2 sub-botones-producto">
            <a href="{{ route('tipo.precios.index',['empresa_id' => $empresa->id]) }}" class="btn btn-outline-secondary btn-block font-roboto-12">
                <i class="fas fa-dollar fa-fw"></i>&nbsp;Tipos de Precio
            </a>
        </div>
    @endcan
    @can('precio.productos.index')
        <div class="col-md-2 sub-botones-producto">
            <a href="{{ route('precio.productos.index',['empresa_id' => $empresa->id]) }}" class="btn btn-outline-secondary btn-block font-roboto-12">
                <i class="fa-solid fa-tag fa-fw"></i>&nbsp;Precios
            </a>
        </div>
    @endcan
</div>
