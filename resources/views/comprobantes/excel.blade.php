@if (isset($comprobantes))
    <table>
        <tr>
            <td align="center"><b>EMP.</b></td>
            <td align="center"><b>TIPO</b></td>
            <td align="center"><b>FECHA</b></td>
            <td align="center"><b>COMPROBANTE</b></td>
            <td align="center"><b>CREADO</b></td>
            <td align="center"><b>CONCEPTO</b></td>
            <td align="center"><b>MONTO</b></td>
            <td align="center"><b>ESTADO</b></td>
            <td align="center"><b>CREADO POR</b></td>
            @can('comprobantef.index')
                <td align="center"><b>COPIA</b></td>
            @endcan
        </tr>
        @foreach ($comprobantes as $datos)
            <tr>
                <td align="center">{{ $datos->empresa->alias }}</td>
                <td align="center">{{ $datos->tipos }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($datos->fecha)->format('d-m-y') }}</td>
                <td align="center">{{ $datos->nro_comprobante }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($datos->creado)->format('d-m-y') }}</td>
                <td>{{ $datos->concepto }}</td>
                <td>{{ $datos->monto }}</td>
                <td align="center">{{ $datos->status }}</td>
                <td align="center">{{ strtoupper($datos->user->name) }}</td>
                @can('comprobantef.index')
                    <td align="center">
                        @if ($datos->copia == '1')
                            Con copia
                        @else
                            Sin copia
                        @endif
                    </td>
                @endcan
            </tr>
        @endforeach
    </table>
@endif
