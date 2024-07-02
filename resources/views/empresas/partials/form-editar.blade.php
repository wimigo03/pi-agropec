<form action="#" method="post" id="form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="pi_cliente_id" value="{{ $cliente->id }}" id="pi_cliente_id">
    <input type="hidden" name="empresa_id" value="{{ $empresa_cliente->id }}">
    <div class="form-group row">
        <div class="col-md-6 px-0 pr-1 font-roboto-12">
            <label for="cliente" class="d-inline">Cliente</label>
            <input type="text" value="{{ $cliente->nombre }}" class="form-control font-roboto-12" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4 px-0 pr-1 font-roboto-12">
            <label for="nombre_comercial" class="d-inline">Nombre Comercial <i class="fas fa-asterisk"></i></label>
            <input type="text" name="nombre_comercial" value="{{ $empresa_cliente->nombre_comercial }}" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-md-4 pr-1 pl-1 font-roboto-12">
            <label for="logo" class="d-inline">Logo</label>
            <input type="file" name="logo" class="form-control font-roboto-12">
        </div>
        <div class="col-md-4 px-0 pl-1 font-roboto-12">
            <label for="cover" class="d-inline">Cover</label>
            <input type="file" name="cover" class="form-control font-roboto-12">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-8 px-0 pr-1 font-roboto-12">
            <label for="direccion" class="d-inline">Direccion <i class="fas fa-asterisk"></i></label>
            <input type="text" name="direccion" value="{{ $empresa_cliente->direccion }}" class="form-control font-roboto-12">
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
            <label for="telefono" class="d-inline">Telefono</label>
            <input type="text" name="telefono" value="{{ $empresa_cliente->telefono }}" class="form-control font-roboto-12" onkeypress="return valideNumberInteger(event);">
        </div>
        <div class="col-md-2 px-0 pr-1 font-roboto-12">
            <label for="alias" class="d-inline">Alias <i class="fas fa-asterisk"></i></label>
            <input type="text" name="alias" value="{{ $empresa_cliente->alias }}" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-3 px-0 pr-1">
            <label for="modulo" class="d-inline">Modulos</label>
            <select id="modulo_id" class="form-control select2">
                <option value="">-</option>
                @foreach ($modulos as $index => $value)
                    <option value="{{ $index }}" @if(old('modulo_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 pr-1 pl-1 text-right">
            <br>
            <span class="tts:left tts-slideIn tts-custom" aria-label="Agregar">
                <button type="button" class="btn btn-outline-success font-roboto-12" onclick="agregar();">
                    <i class="fa fa-plus fa-fw"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="form-group row font-roboto-12 abs-center"">
        <div class="col-md-4 px-0 pr-1 table-responsive">
            <table id="detalle_tabla" class="table display table-bordered responsive hover-orange" style="width:100%;">
                {{--<thead>
                    <tr class="font-roboto-11">
                        <td class="text-center p-1"><b><i class="fa-solid fa-bars"></i></b></td>
                    </tr>
                </thead>--}}
                <tbody>
                    @foreach ($modulos_empresas as $datos)
                        <tr class="font-roboto-11">
                            <input type='hidden' class='modulo_id' value='{{ $datos->modulo->id }}'>
                            <td class="text-justify p-1">{{ $datos->modulo->nombre }}</td>
                            <td class="text-center p-1">{{ $datos->status }}</td>
                            <td class="text-center p-1">
                                @if ($datos->estado == '1')
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                        <a href="{{ route('empresas.modulo.deshabilitar',$datos->id) }}" class="badge-with-padding badge badge-danger" onclick="return confirm('¿Estás seguro de que deseas deshabilitar este módulo?');">
                                            <i class="fas fa-arrow-alt-circle-down fa-fw"></i>
                                        </a>
                                    </span>
                                @else
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                        <a href="{{ route('empresas.modulo.habilitar',$datos->id) }}" class="badge-with-padding badge badge-success">
                                            <i class="fas fa-arrow-alt-circle-up fa-fw"></i>
                                        </a>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
