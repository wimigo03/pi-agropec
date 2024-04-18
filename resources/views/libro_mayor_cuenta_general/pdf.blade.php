<!DOCTYPE html>
<html lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>REPORTE::PI-RESTO</title>
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
                        _*LIBRO MAYOR POR CUENTA - GENERAL*_
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
                <td><b>CUENTA:</b>&nbsp;</td>
                <td colspan="5">{{ $plan_cuenta->codigo . ' ' . $plan_cuenta->nombre }}</td>
            </tr>
            <tr>
                <td><b>DESDE:</b>&nbsp;</td>
                <td>{{ $fecha_i }}</td>
                <td><b>SALDO INICIAL:</b>&nbsp;</td>
                <td>BS. {{ number_format($saldo,2,'.',',') }}</td>
                <td><b>Total Debe:</b>&nbsp;</td>
                <td>BS. {{ number_format($total_debe,2,'.',',') }}</td>
            </tr>
            <tr>
                <td><b>HASTA:</b>&nbsp;</td>
                <td>{{ $fecha_f }}</td>
                <td><b>SALDO FINAL:</b>&nbsp;</td>
                <td>BS. {{ number_format($saldo_final,2,'.',',') }}</td>
                <td><b>Total Haber:</b>&nbsp;</td>
                <td>BS. {{ number_format($total_haber,2,'.',',') }}</td>
            </tr>
        </table>
        <br>
        <table class="font-verdana-9">
            <thead class="linea-inferior">
                <tr>
                    <th>FECHA</th>
                    <th>COMPROBANTE</th>
                    <th>PROYECTO</th>
                    <th>AUXILIAR</th>
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
                    <td class="align-center">{{ $datos->proyecto }}</td>
                    <td class="align-center">{{ $datos->auxiliar }}</td>
                    <td class="align-center">{{ $datos->nro_cheque }}</td>
                    <td>{{ $datos->glosa }}</td>
                    <td class="align-right">{{ number_format($datos->debe,2,'.',',') }}</td>
                    <td class="align-right">{{ number_format($datos->haber,2,'.',',') }}</td>
                    @php
                        if($datos->debe > 0){
                            $saldo += $datos->debe;
                        }else{
                            $saldo -= $datos->haber;
                        }
                    @endphp
                    <td class="align-right">{{ number_format($saldo,2,'.',',') }}</td>
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
