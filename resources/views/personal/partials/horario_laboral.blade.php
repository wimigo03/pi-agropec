<br>
<div class="form-group row">
    <div class="col-md-3 pr-1 font-roboto-12">
        <label for="horario_id" class="d-inline">Horario</label><br>
        <select name="horario_id" id="horario_id" class="form-control select2">
            <option value="">-</option>
            @foreach ($horarios as $index => $value)
                <option value="{{ $index }}" @if(old('horario_id') == $index) selected @endif >{{ $value }}</option>
            @endforeach
                <option value="_MANUAL_" @if(old('horario_id') == '_MANUAL_') selected @endif >MANUAL</option>
        </select>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-12 font-roboto-12">
        <center>
            <table id="tabla-datos-oficina" class="table" style="width: 70%">
                <thead>
                    <tr class="font-roboto-12">
                        <td class="text-left p-1"><b>DIA</b></td>
                        <td class="text-center p-1"><b>ENTRADA</b></td>
                        <td class="text-center p-1"><b>SALIDA</b></td>
                        <td class="text-center p-1"><b>ENTRADA</b></td>
                        <td class="text-center p-1"><b>SALIDA</b></td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <table id="tabla-datos-manual" class="table" style="width: 70%">
                <thead>
                    <tr class="font-roboto-12">
                        <td class="text-left p-1"><b>DIA</b></td>
                        <td class="text-center p-1"><b>ENTRADA</b></td>
                        <td class="text-center p-1"><b>SALIDA</b></td>
                        <td class="text-center p-1"><b>ENTRADA</b></td>
                        <td class="text-center p-1"><b>SALIDA</b></td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="font-roboto-12">
                        <td class="text-left p-1" style="vertical-align: middle">
                            LUNES
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_inicio_lunes" value="{{ old('dia_inicio_lunes') ? old('dia_inicio_lunes') : '08:00' }}" step="60" id="dia_inicio_lunes" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_final_lunes" value="{{ old('dia_final_lunes')  ? old('dia_final_lunes') : '12:00' }}" step="60" id="dia_final_lunes" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_inicio_lunes" value="{{ old('tarde_inicio_lunes')  ? old('tarde_inicio_lunes') : '15:00' }}" step="60" id="tarde_inicio_lunes" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_final_lunes" value="{{ old('tarde_final_lunes') ? old('tarde_final_lunes') : '19:00'  }}" step="60" id="tarde_final_lunes" class="form-control font-roboto-12 text-center">
                        </td>
                    </tr>
                    <tr class="font-roboto-12">
                        <td class="text-left p-1" style="vertical-align: middle">
                            MARTES
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_inicio_martes" value="{{ old('dia_inicio_martes') ? old('dia_inicio_martes') : '08:00' }}" step="60" id="dia_inicio_martes" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_final_martes" value="{{ old('dia_final_martes') ? old('dia_final_martes') : '12:00' }}" step="60" id="dia_final_martes" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_inicio_martes" value="{{ old('tarde_inicio_martes') ? old('tarde_inicio_martes') : '15:00' }}" step="60" id="tarde_inicio_martes" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_final_martes" value="{{ old('tarde_final_martes') ? old('tarde_final_martes') : '19:00' }}" step="60" id="tarde_final_martes" class="form-control font-roboto-12 text-center">
                        </td>
                    </tr>
                    <tr class="font-roboto-12">
                        <td class="text-left p-1" style="vertical-align: middle">
                            MIERCOLES
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_inicio_miercoles" value="{{ old('dia_inicio_miercoles') ? old('dia_inicio_miercoles') : '08:00' }}" step="60" id="dia_inicio_miercoles" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_final_miercoles" value="{{ old('dia_final_miercoles') ? old('dia_final_miercoles') : '12:00' }}" step="60" id="dia_final_miercoles" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_inicio_miercoles" value="{{ old('tarde_inicio_miercoles') ? old('tarde_inicio_miercoles') : '15:00' }}" step="60" id="tarde_inicio_miercoles" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_final_miercoles" value="{{ old('tarde_final_miercoles') ? old('tarde_final_miercoles') : '19:00' }}" step="60" id="tarde_final_miercoles" class="form-control font-roboto-12 text-center">
                        </td>
                    </tr>
                    <tr class="font-roboto-12">
                        <td class="text-left p-1" style="vertical-align: middle">
                            JUEVES
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_inicio_jueves" value="{{ old('dia_inicio_jueves') ? old('dia_inicio_jueves') : '08:00' }}" step="60" id="dia_inicio_jueves" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_final_jueves" value="{{ old('dia_final_jueves') ? old('dia_final_jueves') : '12:00' }}" step="60" id="dia_final_jueves" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_inicio_jueves" value="{{ old('tarde_inicio_jueves') ? old('tarde_inicio_jueves') : '15:00' }}" step="60" id="tarde_inicio_jueves" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_final_jueves" value="{{ old('tarde_final_jueves') ? old('tarde_final_jueves') : '19:00' }}" step="60" id="tarde_final_jueves" class="form-control font-roboto-12 text-center">
                        </td>
                    </tr>
                    <tr class="font-roboto-12">
                        <td class="text-left p-1" style="vertical-align: middle">
                            VIERNES
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_inicio_viernes" value="{{ old('dia_inicio_viernes') ? old('dia_inicio_viernes') : '08:00' }}" step="60" id="dia_inicio_viernes" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_final_viernes" value="{{ old('dia_final_viernes') ? old('dia_final_viernes') : '12:00' }}" step="60" id="dia_final_viernes" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_inicio_viernes" value="{{ old('tarde_inicio_viernes') ? old('tarde_inicio_viernes') : '15:00' }}" step="60" id="tarde_inicio_viernes" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_final_viernes" value="{{ old('tarde_final_viernes') ? old('tarde_final_viernes') : '19:00' }}" step="60" id="tarde_final_viernes" class="form-control font-roboto-12 text-center">
                        </td>
                    </tr>
                    <tr class="font-roboto-12">
                        <td class="text-left p-1" style="vertical-align: middle">
                            SABADO
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_inicio_sabado" value="{{ old('dia_inicio_sabado') ? old('dia_inicio_sabado') : '08:00' }}" step="60" id="dia_inicio_sabado" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_final_sabado" value="{{ old('dia_final_sabado') ? old('dia_final_sabado') : '12:00' }}" step="60" id="dia_final_sabado" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_inicio_sabado" value="{{ old('tarde_inicio_sabado') }}" step="60" id="tarde_inicio_sabado" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_final_sabado" value="{{ old('tarde_final_sabado') }}" step="60" id="tarde_final_sabado" class="form-control font-roboto-12 text-center">
                        </td>
                    </tr>
                    <tr class="font-roboto-12">
                        <td class="text-left p-1" style="vertical-align: middle">
                            DOMINGO
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_inicio_domingo" value="{{ old('dia_inicio_domingo') }}" step="60" id="dia_inicio_domingo" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="dia_final_domingo" value="{{ old('dia_final_domingo') }}" step="60" id="dia_final_domingo" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_inicio_domingo" value="{{ old('tarde_inicio_domingo') }}" step="60" id="tarde_inicio_domingo" class="form-control font-roboto-12 text-center">
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle">
                            <input type="time" name="tarde_final_domingo" value="{{ old('tarde_final_domingo') }}" step="60" id="tarde_final_domingo" class="form-control font-roboto-12 text-center">
                        </td>
                    </tr>
                </tbody>
            </table>
        </center>
    </div>
</div>
