<div class="form-group row">
    <div class="col-md-8 pr-1">
        <div class="card card-body" style="border-top: none; border-radius: 0px;">
            @if (isset($categorias_platos))
                <div class="row">
                    <div class="col-md-10">
                        <div id="treeview"></div>
                    </div>
                    <div class="col-md-2 text-right">
                        <i class="fa fa-spinner custom-spinner fa-spin fa-fw spinner-btn" style="display: none;"></i>
                        @if (count($estado_platos) == 1)
                            <div class="form-group row" style="margin-top: -15px;" id="btn_todos_los_platos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Mostrar Todos" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-primary font-verdana" onclick="todos_los_platos();">
                                            <i class="fa-solid fa-users-gear fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="form-group row" style="margin-top: -15px;" id="btn_solo_habilitados_platos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Mostrar Solo Habilitados" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-success font-verdana" onclick="solo_habilitados_platos();">
                                            <i class="fa-solid fa-users-gear fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endif
                        @can('categorias.create.platos.master')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_platos_master">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Crear Categoria Master" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-secondary font-verdana" onclick="crear_platos_master();">
                                            <i class="fa-solid fa-user-plus fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('categorias.create.platos')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_sub_platos_master">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Crear SubCategoria" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-secondary font-verdana" onclick="crear_platos();">
                                            <i class="fa-solid fa-user-minus fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('categorias.habilitar')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_deshabilitar_platos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-danger font-verdana" onclick="deshabilitar_platos();">
                                            <i class="fa-solid fa-user-xmark fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('categorias.habilitar')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_habilitar_platos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-success font-verdana" onclick="habilitar_platos();">
                                            <i class="fa-solid fa-user-check fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('categorias.modificar')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_modificar_platos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-secondary font-verdana" onclick="modificar();">
                                            <i class="fa-solid fa-users-gear fa-fw"></i>
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
    <div class="col-md-4 pl-1">
        <div class="card card-body" style="border-top: none; border-radius: 0px;">
            <div id="contenido">
                <input type="hidden" value="#" name="categoria_id" id="categoria_id">
                <input type="hidden" value="1" id="tipo_platos">
                @if (isset($categorias_platos))
                    @include('categorias.partials.contenido')
                @endif
            </div>
        </div>
    </div>
</div>
