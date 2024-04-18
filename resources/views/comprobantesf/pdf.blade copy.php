<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>COMPROBANTE</title>
        <style>
            html {
                margin: 20px 50px 30px 50px;
            }
            
            body {
                font-family: verdana,arial,helvetica;
                font-size: 10px;
            }

            .table {
                border-collapse: collapse;
                border: 1px solid black;
            }

            .table td, th {
                /*border: 1px solid black;*/
                padding: 5px;
            }

            .page_break{
                page-break-before: always;
            }
        </style>
    </head>
    <body>
        <table width="100%">
            <tr>
                <td align="center" width="25%" valign="top">
                    <img src={{ public_path($comprobante->empresa->url_cover) }} alt="#" style="width: 100px; height:auto;"/>
                    <br>
                    <font size="5px">
                        {{ $comprobante->empresa->nombre_comercial }}
                        <br>
                        {{ $comprobante->empresa->direccion }} - NIT {{ $comprobante->empresa->nit }}
                    </font>
                </td>
                <td align="center" valign="bottom">
                    <font size="15px">
                        <b>
                            COMPROBANTE DE {{ App\Models\Comprobante::TIPOS[$comprobante->tipo] }}
                        </b>
                    </font>
                    <br>
                    <font size="13px"><b>{{ $comprobante->status }}</b></font>
                </td>
                <td align="center" width="25%" valign="top">
                    <font size="9px">
                        <table border="1" width="100%">
                            <tr>
                                <td><b>Nro.-</b></td>
                                <td>{{ $comprobante->nro_comprobante }}</td>
                            </tr>
                            <tr>
                                <td><b>Fecha.-</b></td>
                                <td>{{ date('d/m/Y', strtotime($comprobante->fecha)) }}</td>
                            </tr>
                            <tr>
                                <td><b>Taza Cambio.-</b></td>
                                <td>{{ $comprobante->tipo_cambio }}</td>
                            </tr>
                            <tr>
                                <td><b>UFV.-</b></td>
                                <td>{{ $comprobante->ufv }}</td>
                            </tr>
                        </table>
                    </font>
                </td>
            </tr>
        </table>
        <br>
        <font size="9px">
            <table width="100%" class="table">
                <tr>
                    <td colspan="3"><b>Por Concepto de.- </b>{{ $comprobante->concepto }}</td>
                </tr>
                <tr>
                    <td><b>Tipo de Moneda.- </b>{{ $comprobante->moneda }}</td>
                    <td><b>Cheques.-</b></td>
                    <td><b>Bancos.-</b></td>
                </tr>
            </table>
        </font>
        <br>
        <table width="100%" class="table">
            <thead style="border-bottom: 1px solid #000000;">
                <tr bgcolor="#FFC107">
                    <td><font color="#ffffff" size="9px"><b>N</b></font></td>
                    <td><font color="#ffffff" size="9px"><b>CODIGO</b></font></td>
                    <td><font color="#ffffff" size="9px"><b>DESCRIPCION / GLOSA</b></font></td>
                    <td align="center"><font color="#ffffff" size="9px"><b>PROY</b></font></td>
                    <td align="right"><font color="#ffffff" size="9px"><b>DEBE(BS.)</b></font></td>
                    <td align="right"><font color="#ffffff" size="9px"><b>HABER(BS.)</b></font></td>
                    <td align="right"><font color="#ffffff" size="9px"><b>DEBE($U$)</b></font></td>
                    <td align="right"><font color="#ffffff" size="9px"><b>HABER($U$)</b></font></td>
                </tr>
            </thead>
            @php
                $cont = 1;
            @endphp
            <tbody>
                @foreach ($comprobante_detalles as $datos)
                    <?php
                        if($cont % 2 == 0){
                            $color = "#ffffff";
                        }else{
                            $color = "#f8f9fa";
                        }
                    ?>
                    <tr bgcolor="{{ $color }}">
                        <td valign="top">
                            <font size="8px">{{ $cont++ }}</font>
                        </td>
                        <td valign="top">
                            <font size="8px">{{ $datos->plan_cuenta->codigo }}</font>
                        </td>
                        <td valign="top">
                            @if($datos->haber != 0)
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td width="10%">&nbsp;</td>
                                        <td>
                                            <font size="8px">
                                                <u>
                                                    {{ $datos->plan_cuenta->nombre }}
                                                    @if ($datos->plan_cuenta_auxiliar_id != null)
                                                        {{ $datos->plan_cuenta_auxiliar->nombre }}
                                                    @endif
                                                </u><br>
                                                {{ $datos->glosa }}
                                            </font>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td width="90%">
                                            <font size="8px">
                                                <u>
                                                    {{ $datos->plan_cuenta->nombre }}
                                                    @if ($datos->plan_cuenta_auxiliar_id != null)
                                                        {{ $datos->plan_cuenta_auxiliar->nombre }}
                                                    @endif
                                                </u><br>
                                                {{ $datos->glosa }}
                                            </font>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            @endif                        
                        </td>
                        <td align="center" valign="top">
                            <font size="8px">{{ $datos->sucursal->nombre }}</font>
                        </td>
                        <td align="right" valign="top">
                            <font size="8px">{{ number_format($datos->debe,2,'.',',') }}</font>
                        </td>
                        <td align="right" valign="top">
                            <font size="8px">{{ number_format($datos->haber,2,'.',',') }}</font>
                        </td>
                        <td align="right" valign="top">
                            <font size="8px">{{ number_format($datos->debe * $comprobante->tipo_cambio,2,'.',',') }}</font>
                        </td>
                        <td align="right" valign="top">
                            <font size="8px">{{ number_format($datos->haber * $comprobante->tipo_cambio,2,'.',',') }}</font>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td align="right" colspan="4">
                        <font size="8px"><b>TOTALES</b></font>
                    </td>
                    <td align="right">
                        <font size="8px"><b>{{ number_format($total_debe,2,'.',',') }}</b></font>
                    </td>
                    <td align="right">
                        <font size="8px"><b>{{ number_format($total_haber,2,'.',',') }}</b></font>
                    </td>
                    <td align="right">
                        <font size="8px"><b>{{ number_format($total_debe * $comprobante->tipo_cambio,2,'.',',') }}</b></font>
                    </td>
                    <td align="right">
                        <font size="8px"><b>{{ number_format($total_haber * $comprobante->tipo_cambio,2,'.',',') }}</b></font>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table>
            <tr>
                <td>
                    <font size="8px"><b>SON: {{ $comprobante->monto . ' (' . $total_en_letras . ')' }}</b></font>
                </td>
            </tr>
        </table>
        <table border="0" align="center" width="100%">
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
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr align="center">
                <td><b>_________________________</b></td>
                <td>&nbsp;</td>
                <td><b>_________________________</b></td>
                <td>&nbsp;</td>
                <td><b>_________________________</b></td>
            </tr>
            <tr align="center">
                <td><font size="9px"><b>Elaborado por</b></font></td>
                <td>&nbsp;</td>
                <td><font size="9px"><b>Revisado por</b></font></td>
                <td>&nbsp;</td>
                <td><font size="9px"><b>Aprobado por</b></font></td>
            </tr>
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
