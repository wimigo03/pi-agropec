<br>
<div class="form-group row">
    <div class="col-md-3 pr-1 font-verdana-bg">
        <label for="horario_id" class="d-inline">Horario</label><br>
        <input type="text" value="{{ $horario_laboral->nombre }}" class="form-control font-verdana-bg" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-12 font-verdana-bg">
        <center>
            <table id="#" class="table" style="width: 70%">
                <thead>
                    <tr class="font-verdana-bg">
                        <td class="text-left p-1"><b>DIA</b></td>
                        <td class="text-center p-1"><b>ENTRADA</b></td>
                        <td class="text-center p-1"><b>SALIDA</b></td>
                        <td class="text-center p-1"><b>ENTRADA</b></td>
                        <td class="text-center p-1"><b>SALIDA</b></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($horario_laboral_detalle as $datos)
                        <tr class="font-verdana-bg">
                            <td class="text-left p-1" style="vertical-align: middle">{{ $datos->dia }}</td>
                            <td class="text-center p-1" style="vertical-align: middle">{{ $datos->entrada_1 }}</td>
                            <td class="text-center p-1" style="vertical-align: middle">{{ $datos->salida_1 }}</td>
                            <td class="text-center p-1" style="vertical-align: middle">{{ $datos->entrada_2 }}</td>
                            <td class="text-center p-1" style="vertical-align: middle">{{ $datos->salida_2 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </center>
    </div>
</div>
