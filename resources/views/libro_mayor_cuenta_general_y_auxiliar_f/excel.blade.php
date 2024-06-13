<table>
    <tr>
        <td colspan="5">
            <b>Cuenta:</b>&nbsp;{{ $plan_cuenta->codigo . ' ' . $plan_cuenta->nombre }}
        </td>
        <td colspan="4">
            <b>Auxiliar:</b>&nbsp;{{ $plan_cuenta_auxiliar->nombre }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Desde:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}
        </td>
        <td colspan="2">
            <b>Saldo Inicial:</b>&nbsp;{{ $saldo_cuenta }}
        </td>
        <td colspan="2">
            <b>Saldo Inicial Auxiliar:</b>&nbsp;{{ $saldo_auxiliar }}
        </td>
        <td colspan="3">
            <b>Total Debe:</b>&nbsp;{{ $total_debe_cuenta }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Hasta:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}
        </td>
        <td colspan="2">
            <b>Saldo Final:</b>&nbsp;{{ $saldo_final_cuenta }}
        </td>
        <td colspan="2">
            <b>Saldo Final Auxiliar:</b>&nbsp;{{ $saldo_final_auxiliar }}
        </td>
        <td colspan="3">
            <b>Total Haber:</b>&nbsp;{{ $total_haber_cuenta }}
        </td>
    </tr>
</table>
<table>
    <tr>
        <td align="center"><b>FECHA</b></td>
        <td align="center"><b>COMPROBANTE</b></td>
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
                    $saldo_cuenta += $datos->debe;
                }else{
                    $saldo_cuenta -= $datos->haber;
                }
            @endphp
            <td>
                {{ $saldo_cuenta }}
            </td>
        </tr>
    @endforeach
</table>
