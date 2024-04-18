<div class="form-group row">
    <div class="col-md-12">
        <table class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-11">
                    <td class="text-left p-1"><b>COD.</b></td>
                    <td class="text-left p-1"><b>NOMBRE COMERCIAL</b></td>
                    <td class="text-left p-1"><b>ALIAS</b></td>
                    <td class="text-left p-1"><b>URL_LOGO</b></td>
                    <td class="text-left p-1"><b>DIRECCION</b></td>
                    <td class="text-left p-1"><b>TELEF.</b></td>
                    <td class="text-left p-1"><b>URL_COVER</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($empresas as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->id }}</td>
                        <td class="text-left p-1">{{ $datos->nombre_comercial }}</td>
                        <td class="text-left p-1">{{ $datos->alias }}</td>
                        <td class="text-left p-1">
                            <a href="{{ asset($datos->url_logo) }}" target="_blank">
                                {{ $datos->url_logo }}
                            </a>
                        </td>
                        <td class="text-left p-1">{{ $datos->direccion }}</td>
                        <td class="text-left p-1">{{ $datos->telefono }}</td>
                        <td class="text-left p-1">
                            <a href="{{ asset($datos->url_cover) }}" target="_blank">
                                {{ $datos->url_cover }}
                            </a>
                        </td>
                        <td class="text-center p-1">{{ $datos->status }}</td>
                        <td class="text-center p-1">
                            <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                <a href="{{ route('empresas.editar',$datos->id) }}" class="badge-with-padding badge badge-warning">
                                    <i class="fas fa-edit fa-fw"></i>
                                </a>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row font-roboto-12">
            <div class="col-md-6">
                <p class="text- muted">Mostrando
                    <strong>{{$empresas->count()}}</strong> registros de
                    <strong>{{$empresas->total()}}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $empresas->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
