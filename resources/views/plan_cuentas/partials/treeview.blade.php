@if (isset($plan_de_cuentas))
    <div class="form-group row">
    <div class="col-md-8 px-1 pr-1">
        <div class="card card-body">
            @if (isset($plan_de_cuentas))
                <div class="row">
                    <div class="col-md-10 px-1 pr-1">
                        <div id="treeview"></div>
                    </div>
                    <div class="col-md-2 px-4 text-right">
                        <i class="fa fa-spinner custom-spinner fa-spin fa-fw spinner-btn" style="display: none;"></i>
                        @if (count($estado) == 1)
                            <div class="form-group row" id="btn_todos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Mostrar Todos" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-primary font-verdana" onclick="todos();">
                                            <i class="fa-solid fa-users-gear fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="form-group row" id="btn_solo_habilitados">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Mostrar Solo Habilitados" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-success font-verdana" onclick="solo_habilitados();">
                                            <i class="fa-solid fa-users-gear fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endif
                        {{--@can('plan.cuentas.create')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_master">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Crear Plan de Cuenta Principal" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-secondary font-verdana" onclick="create();">
                                            <i class="fa-solid fa-plus fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan--}}
                        @can('plan.cuentas.create')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_sub_master">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Crear Plan de Cuenta Dependiente" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-info font-verdana" onclick="createSub();">
                                            <i class="fa-solid fa-folder-tree fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('plan.cuentas.habilitar')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_deshabilitar">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-danger font-verdana" onclick="deshabilitar();">
                                            <i class="fa-solid fa-xmark fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('plan.cuentas.habilitar')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_habilitar">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-success font-verdana" onclick="habilitar();">
                                            <i class="fa-solid fa-check fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('plan.cuentas.editar')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_modificar">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-warning font-verdana" onclick="editar();">
                                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 px-1 pl-1">
        <div class="card card-body">
            <div id="contenido">
                <input type="hidden" value="#" name="plancuenta_id" id="plancuenta_id">
                @if (isset($plan_de_cuentas))
                    @include('plan_cuentas.partials.contenido')
                @endif
            </div>
        </div>
    </div>
    </div>
@endif
