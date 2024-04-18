<br>
<div class="form-group row">
    <div class="col-md-3 pr-1 font-verdana-bg">
        <label for="profesion_ocupacion" class="d-inline">Profesion/Ocupacion</label>
        <input type="text" value="{{ $personal_laboral->profesion_ocupacion }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-3 pr-1 pl-1 font-verdana-bg">
        <label for="nro_cuenta" class="d-inline">Nro. Cuenta</label>
        <input type="text" value="{{ $personal_laboral->nro_cuenta }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-6 pl-1 font-verdana-bg">
        <label for="banco" class="d-inline">Banco</label>
        <input type="text" value="{{ $personal_laboral->banco }}" class="form-control font-verdana-bg" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-2 pr-1 font-verdana-bg">
        <label for="biometrico" class="d-inline">ID Biometrico</label>
        <input type="text" value="{{ $personal_laboral->biometrico_id }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-3 pr-1 pl-1 font-verdana-bg">
        <label for="tipo_contrato" class="d-inline">Tipo Contrato</label>
        <input type="text" value="{{ $personal_laboral->tipo_contrato }}" class="form-control font-verdana-bg" readonly>
    </div>
    @if ($personal_laboral->tipo_contrato == 'PLAZO FIJO')
        <div class="col-md-3 pr-1 pl-1 font-verdana-bg">
            <label for="fecha_conclusion_contrato" class="d-inline">Fecha Conculsion Contrato</label>
            <input type="text" value="{{ \Carbon\Carbon::parse($personal_laboral->fecha_conclusion_contrato)->format('d/m/Y') }}" id="fecha_conclusion_contrato" class="form-control font-verdana-bg" readonly data-language="es" readonly>
        </div>
    @endif
</div>
<div class="form-group row abs-center">
    <div class="col-md-2 pr-1 font-verdana-bg">
        <label for="total_haber_basico" class="d-inline">Total Haber Basico</label>
        <input type="text" value="{{ $personal_laboral->total_haber_basico }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
        <label for="total_bono" class="d-inline">Total Bono</label>
        <input type="text" value="{{ $personal_laboral->total_bono }}" class="form-control font-verdana-bg" readonly>
    </div>
    <div class="col-md-2 pl-1 font-verdana-bg">
        <label for="total_ganado" class="d-inline">Total Ganado</label>
        <input type="text" value="{{ $personal_laboral->total_ganado }}" class="form-control font-verdana-bg" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-12 font-verdana-bg">
        <div id="accordion">
            @if ($personal_contrato_fiscal != null)
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <input type="checkbox" id="checkboxOne" class="ml-2" name="checkboxOne" {{ $personal_contrato_fiscal != null ? 'checked' : '' }}>
                            <span class="btn btn-link text-dark font-verdana-bg-bgt disabled" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-file-contract"></i>&nbsp;F
                            </span>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-3 pr-1 font-verdana-bg">
                                    <label for="fecha_ingreso_fiscal" class="d-inline">Fecha Ingreso</label>
                                    <input type="text" value="{{ \Carbon\Carbon::parse($personal_contrato_fiscal->fecha_ingreso)->format('d/m/Y') }}" class="form-control font-verdana-bg" readonly>
                                </div>
                                <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
                                    <label for="haber_basico_fiscal" class="d-inline">Haber Basico</label>
                                    <input type="text" value="{{ $personal_contrato_fiscal->sueldo }}" class="form-control font-verdana-bg" readonly>
                                </div>
                                <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
                                    <label for="tipo_bono_fiscal" class="d-inline">Tipo Bono</label>
                                    <input type="text" value="{{ $personal_contrato_fiscal->tipo_bono }}" class="form-control font-verdana-bg" readonly>
                                </div>
                                <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
                                    <label for="bono_fiscal" class="d-inline">Bono</label>
                                    <input type="text" value="{{ $personal_contrato_fiscal->bono }}" class="form-control font-verdana-bg" readonly>
                                </div>
                                <div class="col-md-3 pl-1 font-verdana-bg">
                                    <label for="afp_id" class="d-inline">Tipo AFP</label>
                                    <input type="text" value="{{ $personal_contrato_fiscal->afp != null ? $personal_contrato_fiscal->afp->nombre : '#' }}" class="form-control font-verdana-bg" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($personal_contrato_interno != null)
                <div class="card">
                    <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <input type="checkbox" id="checkboxTwo" class="ml-2" name="checkboxTwo" {{ $personal_contrato_interno != null ? 'checked' : '' }}>
                        <span class="btn btn-link collapsed text-dark font-verdana-bg-bgt disabled" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <i class="fas fa-file-contract"></i>&nbsp;I
                        </span>
                    </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-3 pr-1 font-verdana-bg">
                                <label for="fecha_ingreso_interna" class="d-inline">Fecha Ingreso</label>
                                <input type="text" value="{{ \Carbon\Carbon::parse($personal_contrato_interno->fecha_ingreso)->format('d/m/Y') }}" class="form-control font-verdana-bg" readonly>
                            </div>
                            <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
                                <label for="haber_basico_interno" class="d-inline">Haber Basico</label>
                                <input type="text" value="{{ $personal_contrato_interno->sueldo }}" class="form-control font-verdana-bg" readonly>
                            </div>
                            <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
                                <label for="tipo_bono_interno" class="d-inline">Tipo Bono</label>
                                <input type="text" value="{{ $personal_contrato_interno->tipo_bono }}" class="form-control font-verdana-bg" readonly>
                            </div>
                            <div class="col-md-2 pl-1 font-verdana-bg">
                                <label for="bono_interno" class="d-inline">Bono</label>
                                <input type="text" value="{{ $personal_contrato_interno->bono }}" class="form-control font-verdana-bg" readonly>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            @endif
            @if ($personal_contrato_servicio != null)
                <div class="card">
                    <div class="card-header" id="headingTree">
                    <h5 class="mb-0">
                        <input type="checkbox" id="checkboxTree" class="ml-2" name="checkboxTree" {{ $personal_contrato_servicio != null ? 'checked' : '' }}>
                        <span class="btn btn-link collapsed text-dark font-verdana-bg-bgt disabled" data-toggle="collapse" data-target="#collapseTree" aria-expanded="false" aria-controls="collapseTree">
                            <i class="fas fa-file-contract"></i>&nbsp;S
                        </span>
                    </h5>
                    </div>
                    <div id="collapseTree" class="collapse" aria-labelledby="headingTree">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-3 pr-1 font-verdana-bg">
                                <label for="fecha_ingreso_servicio" class="d-inline">Fecha Ingreso</label>
                                <input type="text" value="{{ \Carbon\Carbon::parse($personal_contrato_servicio->fecha_ingreso)->format('d/m/Y') }}" class="form-control font-verdana-bg" readonly>
                            </div>
                            <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
                                <label for="haber_basico_servicio" class="d-inline">Haber Basico</label>
                                <input type="text" value="{{ $personal_contrato_interno->sueldo }}" class="form-control font-verdana-bg" readonly>
                            </div>
                            <div class="col-md-2 pr-1 pl-1 font-verdana-bg">
                                <label for="tipo_bono_servicio" class="d-inline">Tipo Bono</label>
                                <input type="text" value="{{ $personal_contrato_interno->tipo_bono }}" class="form-control font-verdana-bg" readonly>
                            </div>
                            <div class="col-md-2 pl-1 font-verdana-bg">
                                <label for="bono_servicio" class="d-inline">Bono</label>
                                <input type="text" value="{{ $personal_contrato_interno->bono }}" class="form-control font-verdana-bg" readonly>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
