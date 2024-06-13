<table>
    <tr>
        <td colspan="9">
            <b>Empresa:</b>&nbsp;{{ $empresa->nombre_comercial }}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <b>Desde:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}
        </td>
        <td colspan="3">
            <b>Desde Cuenta:</b>&nbsp;{{ $plan_cuenta1->codigo . ' ' . $plan_cuenta1->nombre }}
        </td>
        <td colspan="3">
            <b>Total Debe:</b>&nbsp;{{ $total_debe }}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <b>Hasta:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}
        </td>
        <td colspan="3">
            <b>Hasta Cuenta:</b>&nbsp;{{ $plan_cuenta2->codigo . ' ' . $plan_cuenta2->nombre }}
        </td>
        <td colspan="3">
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
        <td align="center"><b>CENTRO</b></td>
        <td align="center"><b>SUBCENTRO</b></td>
        {{--<td align="center"><b>CHEQUE</b></td>--}}
        <td align="center"><b>GLOSA</b></td>
        <td align="center"><b>DEBE</b></td>
        <td align="center"><b>HABER</b></td>
        <td align="center"><b>SALDO</b></td>
    </tr>
    @php
        $cuenta_actual = -1;
    @endphp
    @foreach ($comprobantes as $datos)
        @php
            if($datos->plan_cuenta_id != $cuenta_actual){
                $saldo_actual = isset($saldos_cuentas[$datos->plan_cuenta_id]) ? $saldos_cuentas[$datos->plan_cuenta_id] : 0;
                $cuenta_actual = $datos->plan_cuenta_id;
            }
            $saldo_actual += $datos->debe - $datos->haber;
        @endphp
        <tr>
            <td align="center">
                {{ $datos->fecha }}
            </td>
            <td align="center">
                {{ $datos->nro_comprobante }}&nbsp;<b>{{ $datos->estado_abreviado}}</b>
            </td>
            <td align="center">
                {{ $datos->codigo_contable . ' ' . $datos->cuenta_contable }}
            </td>
            <td align="center">
                {{ $datos->auxiliar }}
            </td>
            <td align="center">
                {{ $datos->centro }}
            </td>
            <td align="center">
                {{ $datos->subcentro }}
            </td>
            {{--<td align="center">
                {{ $datos->nro_cheque }}
            </td>--}}
            <td>
                {{ $datos->glosa }}
            </td>
            <td>
                {{ $datos->debe }}
            </td>
            <td>
                {{ $datos->haber }}
            </td>
            <td>
                {{ $saldo_actual }}
            </td>
        </tr>
    @endforeach
</table>
