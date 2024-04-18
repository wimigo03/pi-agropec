<div class="form-group row">
    <div class="col-md-12">
        <table class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-11">
                    <td class="text-left p-1"><b>ID</b></td>
                    <td class="text-left p-1"><b>MODULO</b></td>
                    <td class="text-left p-1"><b>TITULO</b></td>
                    <td class="text-left p-1"><b>NOMBRE</b></td>
                    <td class="text-left p-1"><b>DESCRIPCION</b></td>
                    <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->id }}</td>
                        <td class="text-left p-1">{{ $datos->modulo->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->title }}</td>
                        <td class="text-left p-1">{{ $datos->name }}</td>
                        <td class="text-left p-1">{{ $datos->description }}</td>
                        <td class="text-center p-1">
                            <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                <a href="{{ route('permissions.editar',$datos->id) }}" class="badge-with-padding badge badge-warning">
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
                    <strong>{{$permissions->count()}}</strong> registros de
                    <strong>{{$permissions->total()}}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $permissions->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

