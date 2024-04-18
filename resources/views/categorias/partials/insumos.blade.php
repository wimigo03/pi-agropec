<div class="form-group row">
    <div class="col-md-8 pr-1">
        <div class="card card-body" style="border-top: none; border-radius: 0px;">
            @if (isset($categorias_insumos))
                <div class="row">
                    <div class="col-md-10">
                        <div id="treeview_insumos"></div>
                    </div>
                    <div class="col-md-2 text-right">
                        <i class="fa fa-spinner custom-spinner fa-spin fa-fw spinner-btn" style="display: none;"></i>
                        @if (count($estado_insumos) == 1)
                            <div class="form-group row" style="margin-top: -15px;" id="btn_todos_los_insumos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Mostrar Todos" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-primary font-verdana" onclick="todos_los_insumos();">
                                            <i class="fa-solid fa-users-gear fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="form-group row" style="margin-top: -15px;"  id="btn_solo_habilitados_insumos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Mostrar Solo Habilitados" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-success font-verdana" onclick="solo_habilitados_insumos();">
                                            <i class="fa-solid fa-users-gear fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endif
                        @can('categorias.create.insumos.master')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_insumos_master">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Crear Categoria Master" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-secondary font-verdana" onclick="crear_insumos_master();">
                                            <i class="fa-solid fa-user-plus fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('categorias.create.insumos')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_sub_insumos_master">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Crear SubCategoria" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-secondary font-verdana" onclick="crear_insumos();">
                                            <i class="fa-solid fa-user-minus fa-fw"></i>
                                        </span> 
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('categorias.habilitar')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_deshabilitar_insumos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-danger font-verdana" onclick="deshabilitar_insumos();">
                                            <i class="fa-solid fa-user-xmark fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('categorias.habilitar')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_habilitar_insumos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-success font-verdana" onclick="habilitar_insumos();">
                                            <i class="fa-solid fa-user-check fa-fw"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endcan
                        @can('categorias.modificar')
                            <div class="form-group row" style="margin-top: -15px;" id="btn_modificar_insumos">
                                <div class="col-md-12">
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                        <span class="btns btn btn-sm btn-secondary font-verdana" onclick="modificar_insumos();">
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
            <div id="contenido_insumos">
                <input type="hidden" value="#" name="categoria_insumo_id" id="categoria_insumo_id">    
                <input type="hidden" value="2" id="tipo_insumos">
                @if (isset($categorias_insumos))
                    @include('categorias.partials.contenido_insumos')
                @endif
            </div>
        </div>
    </div>
</div>