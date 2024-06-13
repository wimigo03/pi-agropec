<!DOCTYPE html>
<html lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>REPORTE::PI</title>
    <style>
        <?php echo file_get_contents(public_path('css/styles/font-verdana-pdf.css')); ?>
    </style>
    <body>
        <table>
            <tr>
                <td width="25%" class="font-verdana-6 align-center">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($empresa->url_cover))) }}" class="logo-callejx" alt="#"/>
                    <br>
                    {{ $empresa->nombre_comercial }}
                    <br>
                    {{ $empresa->direccion }} - NIT {{ $empresa->nit }}
                </td>
                <td class="font-verdana-15 align-center align-middle align-inferior">
                    <b>
                        _*LIBRO MAYOR POR CUENTA Y AUXILIAR*_
                    </b>
                </td>
                <td width="25%" class="font-verdana-9 align-center align-superior">
                    &nbsp;
                </td>
            </tr>
        </table>
        <br>
        <table class="font-verdana-9">
            <tr>
                <td><b>Cuenta:</b>&nbsp;</td>
                <td colspan="4">{{ $plan_cuenta->codigo . ' ' . $plan_cuenta->nombre }}</td>
                <td><b>Auxiliar:</b>&nbsp;</td>
                <td colspan="2">{{ $plan_cuenta_auxiliar->nombre }}</td>
            </tr>
            <tr>
                <td><b>Desde:</b>&nbsp;</td>
                <td>{{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}</td>
                <td><b>Saldo inicial:</b>&nbsp;</td>
                <td>BS. {{ number_format($saldo_cuenta,2,'.',',') }}</td>
                <td><b>Saldo auxiliar inicial:</b>&nbsp;</td>
                <td>BS. {{ number_format($saldo_auxiliar,2,'.',',') }}</td>
                <td><b>Total Debe:</b>&nbsp;</td>
                <td>BS. {{ number_format($total_debe_cuenta,2,'.',',') }}</td>
            </tr>
            <tr>
                <td><b>Hasta:</b>&nbsp;</td>
                <td>{{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}</td>
                <td><b>Saldo final:</b>&nbsp;</td>
                <td>BS. {{ number_format($saldo_final_cuenta,2,'.',',') }}</td>
                <td><b>Saldo auxiliar final:</b>&nbsp;</td>
                <td>BS. {{ number_format($saldo_final_auxiliar,2,'.',',') }}</td>
                <td><b>Total Haber:</b>&nbsp;</td>
                <td>BS. {{ number_format($total_haber_cuenta,2,'.',',') }}</td>
            </tr>
        </table>
        <br>
        <table class="font-verdana-9">
            <thead class="linea-inferior">
                <tr>
                    <th>FECHA</th>
                    <th>COMPROBANTE</th>
                    <th>CENTRO</th>
                    <th>SUBCENTRO</th>
                    <th>CHEQUE</th>
                    <th>GLOSA</th>
                    <th>DEBE</th>
                    <th>HABER</th>
                    <th>SALDO</th>
                </tr>
            </thead>
            @foreach ($comprobantes as $datos)
                <tr class="sub-linea-inferior">
                    <td class="align-center">{{ $datos->fecha }}</td>
                    <td class="align-center">{{ $datos->nro_comprobante }}&nbsp;<b>{{ $datos->estado_abreviado}}</b></td>
                    <td class="align-center">{{ $datos->centro }}</td>
                    <td class="align-center">{{ $datos->subcentro }}</td>
                    <td class="align-center">{{ $datos->nro_cheque }}</td>
                    <td>{{ $datos->glosa }}</td>
                    <td class="align-right">{{ number_format($datos->debe,2,'.',',') }}</td>
                    <td class="align-right">{{ number_format($datos->haber,2,'.',',') }}</td>
                    @php
                        if($datos->debe > 0){
                            $saldo_cuenta += $datos->debe;
                        }else{
                            $saldo_cuenta -= $datos->haber;
                        }
                    @endphp
                    <td class="align-right">{{ number_format($saldo_cuenta,2,'.',',') }}</td>
                </tr>
            @endforeach
        </table>
    </body>
</html>
<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("verdana");
            $pdf->text(40, 765, "{{ date('d/m/Y H:i') }} - {{ Auth()->user()->username }}", $font, 6);
            $pdf->text(530, 765, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 6);
        ');
    }
</script>
