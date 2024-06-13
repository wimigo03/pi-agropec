<table>
    <tr>
        <td colspan="10">
            <b>Auxiliar:</b>&nbsp;{{ $plan_cuenta_auxiliar->nombre }}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <b>Desde:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}
        </td>
        <td colspan="3">
            <b>Saldo Inicial:</b>&nbsp;{{ $saldo }}
        </td>
        <td colspan="4">
            <b>Total Debe:</b>&nbsp;{{ $total_debe }}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <b>Hasta:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}
        </td>
        <td colspan="3">
            <b>Saldo Final:</b>&nbsp;{{ $saldo_final }}
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
        <td align="center"><b>CENTRO</b></td>
        <td align="center"><b>SUBCENTRO</b></td>
        <td align="center"><b>CHEQUE</b></td>
        <td align="center"><b>GLOSA</b></td>
        <td align="center"><b>DEBE</b></td>
        <td align="center"><b>HABER</b></td>
        <td align="center"><b>SALDO</b></td>
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
                {{ $datos->codigo . ' ' . $datos->plan_cuenta }}
            </td>
            <td align="center">
                {{ $datos->centro }}
            </td>
            <td align="center">
                {{ $datos->subcentro }}
            </td>
            <td align="center">
                {{ $datos->nro_cheque }}
            </td>
            <td>
                {{ $datos->glosa }}
            </td>
            <td>
                {{ $datos->debe }}
            </td>
            <td>
                {{ $datos->haber }}
            </td>
            @php
                if($datos->debe > 0){
                    $saldo += $datos->debe;
                }else{
                    $saldo -= $datos->haber;
                }
            @endphp
            <td>
                {{ $saldo }}
            </td>
        </tr>
    @endforeach
</table>
