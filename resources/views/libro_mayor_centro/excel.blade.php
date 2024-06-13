<table>
    <tr>
        <td colspan="3">
            <b>Centro:</b>&nbsp;{{ $sub_centro->centro->nombre }}
        </td>
        <td colspan="4">
            <b>SubCentro:</b>&nbsp;{{ $sub_centro->nombre }}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <b>Desde:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}
        </td>
        <td colspan="4">
            <b>Total Debe:</b>&nbsp;{{ $total_debe }}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <b>Hasta:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}
        </td>
        <td colspan="4">
            <b>Total Haber:</b>&nbsp;{{ $total_haber }}
        </td>
    </tr>
</table>
<table>
    <tr>
        <td align="center"><b>FECHA</b></td>
        <td align="center"><b>COMPROBANTE</b></td>
        <td align="center"><b>CUENTA CONTABLE</b></td>
        <td align="center"><b>AUXILIAR</b></td>
        <td align="center"><b>GLOSA</b></td>
        <td align="center"><b>DEBE</b></td>
        <td align="center"><b>HABER</b></td>
    </tr>
    @foreach ($comprobantes as $datos)
        <tr>
            <td align="center">
                {{ $datos->fecha }}
            </td>
            <td align="center">
                {{ $datos->nro_comprobante }}&nbsp;<b>{{ $datos->estado_abreviado}}</b>
            </td>
            <td>
                {{ $datos->cuenta_contable }}
            </td>
            <td>
                {{ $datos->auxiliar }}
            </td>
            <td>
                {{ $datos->glosa }}
            </td>
            <td align="right">
                {{ $datos->debe }}
            </td>
            <td align="right">
                {{ $datos->haber }}
            </td>
        </tr>
    @endforeach
</table>
