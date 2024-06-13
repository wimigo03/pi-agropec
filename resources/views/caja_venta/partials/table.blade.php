<div class="form-group row">
    <div class="col-md-12 px-1">
        <table class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-11 bg-secondary text-white">
                    <td class="text-center p-1"><b>SUCURSAL</b></td>
                    <td class="text-center p-1"><b>FECHA</b></td>
                    <td class="text-center p-1"><b>CODIGO</b></td>
                    <td class="text-center p-1"><b>ASIGNADO A</b></td>
                    <td class="text-center p-1"><b>ASIGNADO POR</b></td>
                    <td class="text-center p-1"><b>MONTO</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @canany(['caja.venta.show'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($cajas_ventas as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-center p-1">{{ $datos->sucursal->nombre }}</td>
                        <td class="text-center p-1">{{ \Carbon\Carbon::parse($datos->fecha_registro)->format('d/m/Y') }}</td>
                        <td class="text-center p-1">{{ $datos->codigo }}</td>
                        <td class="text-center p-1">{{ strtoupper($datos->user->username) }}</td>
                        <td class="text-center p-1">{{ $datos->user_asignado != null ? $datos->user_asignado->username : '' }}</td>
                        <td class="text-right p-1">{{ number_format($datos->monto_apertura,2,'.',',') }}</td>
                        <td class="text-center p-1">
                            <span class="{{ $datos->color_badge_status}}">
                                {{ $datos->status }}
                            </span>
                        </td>
                        @canany(['caja.venta.show'])
                            <td class="text-center p-1">
                                <div class="d-flex justify-content-center">
                                    @can('caja.venta.show')
                                        <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a detalle" style="cursor: pointer;">
                                            <a href="{{ route('caja.venta.show',$datos->id) }}" class="badge-with-padding badge badge-primary">
                                                <i class="fas fa-list fa-fw"></i>
                                            </a>
                                        </span>
                                    @endcan
                                </div>
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row font-roboto-11">
            <div class="col-md-6">
                <p class="text- muted">Mostrando
                    <strong>{{ $cajas_ventas->count() }}</strong> registros de
                    <strong>{{ $cajas_ventas->total() }}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $cajas_ventas->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
