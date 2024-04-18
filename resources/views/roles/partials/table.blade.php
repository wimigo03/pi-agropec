<div class="form-group row abs-center">
    <div class="col-md-6 px-0 pr-1">
        <table class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-left p-1"><b>ID</b></td>
                    <td class="text-left p-1"><b>NOMBRE_ROL</b></td>
                    @canany(['roles.editar'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $datos)
                    <tr class="font-roboto-12">
                        <td class="text-left p-1">{{ $datos->id }}</td>
                        <td class="text-left p-1">{{ $datos->name }}</td>
                        @can('roles.editar')
                            <td class="text-center p-1">
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a detalle" style="cursor: pointer;">
                                    <a href="{{ route('roles.editar_index',$datos->id) }}" class="badge-with-padding badge badge-primary">
                                        <i class="fas fa-list fa-fw"></i>
                                    </a>
                                </span>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row font-roboto-12">
            <div class="col-md-6">
                <p class="text- muted">Mostrando
                    <strong>{{$roles->count()}}</strong> registros de
                    <strong>{{$roles->total()}}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $roles->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
