<!DOCTYPE html>
<html lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>COMPROBANTE</title>
    <style>
        <?php echo file_get_contents(public_path('css/styles/font-verdana-pdf.css')); ?>
    </style>
    <body>
        <table>
            <tr>
                <td width="25%" class="font-verdana-6 align-center">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($comprobante->empresa->url_cover))) }}" class="logo-callejx" alt="#"/>
                    <br>
                    {{ $comprobante->empresa->nombre_comercial }}
                    <br>
                    {{ $comprobante->empresa->direccion }} - NIT {{ $comprobante->empresa->nit }}
                </td>
                <td class="font-verdana-15 align-center align-inferior">
                    <b>
                        COMPROBANTE DE {{ App\Models\Comprobante::TIPOS[$comprobante->tipo] }}
                    </b>
                    <br>
                    <b>{{ $comprobante->status }}</b>
                </td>
                <td width="25%" class="font-verdana-9 align-center align-superior">
                    <table border="1">
                        <tr>
                            <td style="padding: 3px;"><b>Nro.-</b></td>
                            <td style="padding: 3px;">{{ $comprobante->nro_comprobante }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px;"><b>Fecha.-</b></td>
                            <td style="padding: 3px;">{{ date('d-m-Y', strtotime($comprobante->fecha)) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px;"><b>Taza Cambio.-</b></td>
                            <td style="padding: 3px;">{{ $comprobante->tipo_cambio }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px;"><b>UFV.-</b></td>
                            <td style="padding: 3px;">{{ $comprobante->ufv }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="font-verdana-10 align-superior">
            <tr>
                <td width="80%">
                    &nbsp;
                </td>
                <td width="20%">
                    <table border="0">
                        <tr>
                            <td style="padding: 2px;"><b>Gestion.-</b></td>
                            <td style="padding: 2px;">{{ date('Y', strtotime($comprobante->fecha)) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px;"><b>Mes.-</b></td>
                            <td style="padding: 2px;">{{ date('m', strtotime($comprobante->fecha)) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px;"><b>N° Pag..-</b></td>
                            <td style="padding: 2px;">1</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="font-verdana-9">
            @if ($comprobante->tipo == '1')
                <tr>
                    <td colspan="3">
                        <b>Hemos Recibido de.- </b>{{ $comprobante->entregado_recibido }}
                    </td>
                </tr>
            @endif
            @if ($comprobante->tipo == '2')
                <tr>
                    <td colspan="3">
                        <b>Hemos Entregado a.- </b>{{ $comprobante->entregado_recibido }}
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="3"><b>Por Concepto de.- </b>{{ $comprobante->concepto }}</td>
            </tr>
            <tr class="linea-inferior">
                <td><b>Tipo de Moneda.- </b>{{ $comprobante->moneda }}</td>
                <td><b>Cheques.-</b></td>
                <td><b>Bancos.-</b></td>
            </tr>
        </table>
        <br>
        <table class="font-verdana-9">
            <thead class="linea-inferior">
                <tr class="bg-gradient-warning">
                    <th>N</th>
                    <th>CODIGO</th>
                    <th>DESCRIPCION / GLOSA</th>
                    <th>CENTRO</th>
                    <th>SUBCENTRO</th>
                    <th>DEBE(BS.)</th>
                    <th>HABER(BS.)</th>
                    <th>DEBE($U$)</th>
                    <th>HABER($U$)</th>
                </tr>
            </thead>
            @php
                $cont = 1;
            @endphp
            <tbody class="linea-inferior">
                @foreach ($comprobante_detalles as $datos)
                    <?php
                        if($cont % 2 == 0){
                            $color = "#ffffff";
                        }else{
                            $color = "#f8f9fa";
                        }
                    ?>
                    <tr bgcolor="{{ $color }}">
                        <td class="align-superior">{{ $cont++ }}</td>
                        <td class="align-superior">{{ $datos->plan_cuenta->codigo }}</td>
                        <td class="align-superior">
                            <u>
                                {{ $datos->plan_cuenta->nombre }}
                                @if ($datos->plan_cuenta_auxiliar_id != null)
                                    {{ $datos->plan_cuenta_auxiliar->nombre }}
                                @endif
                            </u><br>
                            {{ $datos->glosa }}
                        </td>
                        <td class="align-center align-superior">{{ $datos->centro->abreviatura }}</td>
                        <td class="align-center align-superior">{{ $datos->subcentro->abreviatura }}</td>
                        <td class="align-right align-superior">{{ number_format($datos->debe,2,'.',',') }}</td>
                        <td class="align-right align-superior">{{ number_format($datos->haber,2,'.',',') }}</td>
                        <td class="align-right align-superior">{{ number_format($datos->debe * $comprobante->tipo_cambio,2,'.',',') }}</td>
                        <td class="align-right align-superior">{{ number_format($datos->haber * $comprobante->tipo_cambio,2,'.',',') }}</td>
                    </tr>
            @endforeach
            <tr class="font-verdana-9">
                <td class="align-superior" colspan="5"><b>TOTALES</b></td>
                <td class="align-right align-superior"><b>{{ number_format($total_debe,2,'.',',') }}</b></td>
                <td class="align-right align-superior"><b>{{ number_format($total_haber,2,'.',',') }}</b></td>
                <td class="align-right align-superior"><b>{{ number_format($total_debe * $comprobante->tipo_cambio,2,'.',',') }}</b></td>
                <td class="align-right align-superior"><b>{{ number_format($total_haber * $comprobante->tipo_cambio,2,'.',',') }}</b></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table>
            <tr class="font-verdana-9">
                <td>
                    <b>SON: {{ number_format($comprobante->monto,2,'.',',') . ' (' . $total_en_letras . ')' }}</b>
                </td>
            </tr>
        </table>
        <table class="font-verdana-9">
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr class="align-center">
                <td><b>_________________________</b></td>
                <td>&nbsp;</td>
                <td><b>_________________________</b></td>
                <td>&nbsp;</td>
                <td><b>_________________________</b></td>
            </tr>
            <tr class="align-center">
                <td><b>Elaborado por</b></td>
                <td>&nbsp;</td>
                <td><b>Revisado por</b></td>
                <td>&nbsp;</td>
                <td><b>Aprobado por</b></td>
            </tr>
        </table>
    </body>
</html>
<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("verdana");
            $pdf->text(40, 765, "{{ date('d/m/Y H:i') }} - {{ Auth()->user()->username }}", $font, 7);
            $pdf->text(530, 765, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 7);
        ');
    }
</script>
