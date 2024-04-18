<br>
<div class="form-group row">
    <div class="col-md-3 pr-1 font-roboto-12">
        <label for="profesion_ocupacion" class="d-inline">Profesion/Ocupacion</label>
        <input type="text" name="profesion_ocupacion" value="{{ old('profesion_ocupacion') }}" id="profesion_ocupacion" class="form-control font-roboto-12 obligatorio" oninput="this.value = this.value.toUpperCase(); verificarObligatorio();">
    </div>
    <div class="col-md-3 pr-1 pl-1 font-roboto-12">
        <label for="nro_cuenta" class="d-inline">Nro. Cuenta</label>
        <input type="text" name="nro_cuenta" value="{{ old('nro_cuenta') }}" class="form-control font-roboto-12">
    </div>
    <div class="col-md-6 pl-1 font-roboto-12">
        <label for="banco" class="d-inline">Banco</label>
        <input type="text" name="banco" value="{{ old('banco') }}" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2 pr-1 font-roboto-12">
        <label for="biometrico" class="d-inline">ID Biometrico</label>
        <input type="text" name="biometrico" value="{{ old('biometrico') }}" id="biometrico" class="form-control font-roboto-12" onkeypress="return valideNumberSinDecimal(event);">
    </div>
    <div class="col-md-3 pr-1 pl-1 font-roboto-12">
        <label for="tipo_contrato" class="d-inline">Tipo Contrato</label>
        <div class="select2-container--obligatorio" id="obligatorio_tipo_contrato">
            <select name="tipo_contrato" id="tipo_contrato" class="form-control font-roboto-12 select2">
                <option value="">--Seleccionar--</option>
                <option value="PLAZO FIJO" @if(old('tipo_contrato') == 'PLAZO FIJO') selected @endif>PLAZO FIJO</option>
                <option value="INDEFINIDO" @if(old('tipo_contrato') == 'INDEFINIDO') selected @endif>INDEFINIDO</option>
                <option value="POR SERVICIO" @if(old('tipo_contrato') == 'POR SERVICIO') selected @endif>POR SERVICIO</option>
            </select>
        </div>
    </div>
    <div class="col-md-3 pr-1 pl-1 font-roboto-12" id="form_fecha_contrato_fijo">
        <label for="fecha_contrato_fijo" class="d-inline">Fecha Conclusion Contrato</label>
        <input type="text" name="fecha_contrato_fijo" value="{{ old('fecha_contrato_fijo') }}" id="fecha_contrato_fijo" placeholder="dd/mm/aaaa" class="form-control font-roboto-12" data-language="es" onkeyup=countChars(this);>
    </div>
</div>
<div class="form-group row abs-center">
    <div class="col-md-2 pr-1 font-roboto-12">
        <label for="total_haber_basico" class="d-inline">Total Haber Basico</label>
        <input type="text" name="total_haber_basico" value="{{ old('total_haber_basico') }}" id="total_haber_basico" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-roboto-12">
        <label for="total_bono" class="d-inline">Total Bono</label>
        <input type="text" name="total_bono" value="{{ old('total_bono') }}" id="total_bono" class="form-control font-roboto-12" disabled>
    </div>
    <div class="col-md-2 pl-1 font-roboto-12">
        <label for="total_ganado" class="d-inline">Total Ganado</label>
        <input type="text" name="total_ganado" value="{{ old('total_ganado') }}" id="total_ganado" class="form-control font-roboto-12" disabled>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-12 font-roboto-12">
        <div id="accordion">
            <div class="card-body">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <input type="checkbox" id="checkboxOne" class="ml-2" name="checkboxOne" {{ old('checkboxOne') ? 'checked' : '' }}>
                        <input type="hidden" name="fiscal" id="fiscal">
                        <span class="btn btn-link text-dark font-roboto-12-bgt disabled" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fas fa-file-contract"></i>&nbsp;F
                        </span>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-3 pr-1 font-roboto-12">
                                <label for="fecha_ingreso_fiscal" class="d-inline">Fecha Ingreso</label>
                                <input type="text" name="fecha_ingreso_fiscal" value="{{ old('fecha_ingreso_fiscal') }}" placeholder="dd/mm/aaaa" class="form-control font-roboto-12 obligatorio" id="fecha_ingreso_fiscal" data-language="es" onkeyup=countCharsFechaIngresoFiscal(this); oninput="verificarObligatorio();">
                            </div>
                            <div class="col-md-2 pr-1 pl-1 font-roboto-12">
                                <label for="haber_basico_fiscal" class="d-inline">Haber Basico</label>
                                <input type="text" name="haber_basico_fiscal" value="{{ (old('haber_basico_fiscal') != null) ? old('haber_basico_fiscal') : 0; }}" id="haber_basico_fiscal" class="form-control font-roboto-12 obligatorio" onkeypress="return valideNumberConDecimal(event);" oninput="verificarObligatorio();">
                            </div>
                            <div class="col-md-2 pr-1 pl-1 font-roboto-12">
                                <label for="tipo_bono_fiscal" class="d-inline">Tipo Bono</label>
                                <input type="text" name="tipo_bono_fiscal" value="{{ old('tipo_bono_fiscal') }}" id="tipo_bono_fiscal" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-md-2 pr-1 pl-1 font-roboto-12">
                                <label for="bono_fiscal" class="d-inline">Bono</label>
                                <input type="text" name="bono_fiscal" value="{{ (old('bono_fiscal') != null) ? old('bono_fiscal') : 0; }}" id="bono_fiscal" class="form-control font-roboto-12" onkeypress="return valideNumberConDecimal(event);">
                            </div>
                            <div class="col-md-3 pl-1 font-roboto-12">
                                <label for="afp_id" class="d-inline">Tipo AFP</label>
                                <div class="select2-container--obligatorio" id="obligatorio_afp_id">
                                    <select name="afp_id" id="afp_id" class="form-control select2" onchange="verificarObligatorio();">
                                        <option value="">-</option>
                                        @foreach ($afps as $index => $value)
                                            <option value="{{ $index }}" @if(old('afp_id') == $index) selected @endif >{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
              <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <input type="checkbox" id="checkboxTwo" class="ml-2" name="checkboxTwo" {{ old('checkboxTwo') ? 'checked' : '' }}>
                    <input type="hidden" name="interno" id="interno">
                    <span class="btn btn-link collapsed text-dark font-roboto-12-bgt disabled" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fas fa-file-contract"></i>&nbsp;I
                    </span>
                </h5>
              </div>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-3 pr-1 font-roboto-12">
                            <label for="fecha_ingreso_interna" class="d-inline">Fecha Ingreso</label>
                            <input type="text" name="fecha_ingreso_interna" value="{{ old('fecha_ingreso_interna') }}" placeholder="dd/mm/aaaa" class="form-control font-roboto-12 obligatorio" id="fecha_ingreso_interna" data-language="es" onkeyup=countCharsFechaIngresoInterna(this);>
                        </div>
                        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
                            <label for="haber_basico_interno" class="d-inline">Haber Basico</label>
                            <input type="text" name="haber_basico_interno" value="{{ (old('haber_basico_interno') != null) ? old('haber_basico_interno') : 0; }}" id="haber_basico_interno" class="form-control font-roboto-12 obligatorio" onkeypress="return valideNumberConDecimal(event);">
                        </div>
                        <div class="col-md-2 pr-1 pl-1 font-roboto-12">
                            <label for="tipo_bono_interno" class="d-inline">Tipo Bono</label>
                            <input type="text" name="tipo_bono_interno" value="{{ old('tipo_bono_interno') }}" id="tipo_bono_interno" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-2 pl-1 font-roboto-12">
                            <label for="bono_interno" class="d-inline">Bono</label>
                            <input type="text" name="bono_interno" value="{{ (old('bono_interno') != null) ? old('bono_interno') : 0; }}" id="bono_interno" class="form-control font-roboto-12" onkeypress="return valideNumberConDecimal(event);">
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="card-body">
                <div class="card-header" id="headingTree">
                  <h5 class="mb-0">
                      <input type="checkbox" id="checkboxTree" class="ml-2" name="checkboxTree" {{ old('checkboxTree') ? 'checked' : '' }}>
                      <input type="hidden" name="servicio" id="servicio">
                      <span class="btn btn-link collapsed text-dark font-roboto-12-bgt disabled" data-toggle="collapse" data-target="#collapseTree" aria-expanded="false" aria-controls="collapseTree">
                          <i class="fas fa-file-contract"></i>&nbsp;S
                      </span>
                  </h5>
                </div>
                <div id="collapseTree" class="collapse" aria-labelledby="headingTree">
                  <div class="card-body">
                      <div class="form-group row">
                          <div class="col-md-3 pr-1 font-roboto-12">
                              <label for="fecha_ingreso_servicio" class="d-inline">Fecha Ingreso</label>
                              <input type="text" name="fecha_ingreso_servicio" value="{{ old('fecha_ingreso_servicio') }}" placeholder="dd/mm/aaaa" class="form-control font-roboto-12 obligatorio" id="fecha_ingreso_servicio" data-language="es" onkeyup=countCharsFechaIngresoServicio(this);>
                          </div>
                          <div class="col-md-2 pr-1 pl-1 font-roboto-12">
                              <label for="haber_basico_servicio" class="d-inline">Haber Basico</label>
                              <input type="text" name="haber_basico_servicio" value="{{ (old('haber_basico_servicio') != null) ? old('haber_basico_servicio') : 0; }}" id="haber_basico_servicio" class="form-control font-roboto-12 obligatorio" onkeypress="return valideNumberConDecimal(event);">
                          </div>
                          <div class="col-md-2 pr-1 pl-1 font-roboto-12">
                              <label for="tipo_bono_servicio" class="d-inline">Tipo Bono</label>
                              <input type="text" name="tipo_bono_servicio" value="{{ old('tipo_bono_servicio') }}" id="tipo_bono_servicio" class="form-control font-roboto-12" oninput="this.value = this.value.toUpperCase()">
                          </div>
                          <div class="col-md-2 pl-1 font-roboto-12">
                              <label for="bono_servicio" class="d-inline">Bono</label>
                              <input type="text" name="bono_servicio" value="{{ (old('bono_servicio') != null) ? old('bono_servicio') : 0; }}" id="bono_servicio" class="form-control font-roboto-12" onkeypress="return valideNumberConDecimal(event);">
                          </div>
                      </div>
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>
