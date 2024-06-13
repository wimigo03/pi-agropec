<div class="form-group row">
    <div class="col-md-12 px-1">
        <table class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-11">
                    <td class="text-center p-1"><b>COD.</b></td>
                    <td class="text-center p-1"><b>PAIS</b></td>
                    <td class="text-center p-1"><b>INICIO</b></td>
                    <td class="text-left p-1"><b>RAZON SOCIAL</b></td>
                    <td class="text-left p-1"><b>NOMBRE</b></td>
                    <td class="text-left p-1"><b>TELEFONO</b></td>
                    <td class="text-left p-1"><b>NIT</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-center p-1">{{ $datos->id }}</td>
                        <td class="text-center p-1">{{ strtoupper($datos->_pais->nombre) }}</td>
                        <td class="text-center p-1">{{ $datos->fecha_i }}</td>
                        <td class="text-left p-1">{{ $datos->razon_social }}</td>
                        <td class="text-left p-1">{{ $datos->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->telefono }}</td>
                        <td class="text-left p-1">{{ $datos->nit }}</td>
                        <td class="text-center p-1">{{ $datos->status }}</td>
                        <td class="text-center p-1">
                            <span class="tts:left tts-slideIn tts-custom" aria-label="Ir empresas" style="cursor: pointer;">
                                <a href="{{ route('empresas.index',$datos->id) }}" class="badge-with-padding badge badge-primary">
                                    <i class="fas fa-list fa-fw"></i>
                                </a>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-roboto-11">
                    <td colspan="12">
                        {{ $clientes->appends(Request::all())->links() }}
                        <p class="text-muted">Mostrando
                            <strong>{{$clientes->count()}}</strong> registros de
                            <strong>{{$clientes->total()}}</strong> totales
                        </p>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
