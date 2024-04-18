<form action="#" method="get" id="form_search">
    <input type="hidden" name="role_id" value="{{ $role->id }}">
    <input type="hidden" value="{{ $empresa->id }}" id="empresa_id">
    <div class="form-group row abs-center font-roboto-12">
        <div class="col-md-3 px-0 pr-1">
            <label for="role" class="d-inline">En modificacion</label>
            <input type="text" value="{{ $role->name }}" class="form-control font-roboto-12" disabled>
        </div>
        <div class="col-md-3 pr-1 pl-1 text-center">
            <br>
            <button class="btn btn-outline-primary font-roboto-12" type="button" onclick="procesar();">
                <i class="fas fa-paper-plane"></i>&nbsp;Actualizar
            </button>
            <button class="btn btn-outline-danger font-roboto-12" type="button" onclick="cancelar();">
                &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
            </button>
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
        {{--<div class="col-md-3 pr-1 pl-1">
            <label for="modulo" class="d-inline">Modulos asignados</label>
            <select name="modulo_id" id="modulo_id" class="form-control select2">
                <option value="">-</option>
                <option value="_todos_" @if(request('modulo_id') == '_todos_') selected @endif>_TODOS LOS PERMISOS_</option>
                @foreach ($modulos as $index => $value)
                    <option value="{{ $index }}" @if(request('modulo_id') == $index) selected @endif >{{ $value }}</option>
                @endforeach
            </select>
        </div>--}}
    </div>
</form>
@if (isset($permisosOrdenados))
    <div class="form-group row font-roboto-12">
        <div class="col-md-12">
            <form action="#" method="post" id="form">
                @csrf
                <input type="hidden" name="role_id" value="{{ $role->id }}">
                <div style="height:600px;overflow-y: scroll; width: 100%;">
                    @for ($i = 0; $i < count($permisosOrdenados); $i++)
                        <div class="form-group row font-roboto-12">
                            <div class="col-md-4 pr-1">
                                <ul class="list-group list-group-sm">
                                    <li class="list-group-item list-group-item-dark">
                                        <label class="">
                                            <input type="checkbox" name="modelo" value="0" id="{{ $permisosOrdenados[$i][0] }}" onchange="marcarTodo(this.id);">
                                            {{$permisosOrdenados[$i][0]}}
                                        </label>
                                    </li>
                                    @for ($j = 1; $j < count($permisosOrdenados[$i]); $j+=2)
                                        <li class="list-group-item">
                                            <input type="checkbox" name="permissions[]" value="{{ $permisosOrdenados[$i][$j] }}" class="{{ $permisosOrdenados[$i][0] }}" {{ $role->hasPermissionTo($permisosOrdenados[$i][$j]) ? 'checked' : '' }}>
                                            {{$permisosOrdenados[$i][$j + 1]}}
                                        </li>
                                    @endfor
                                    @php
                                        $i++;
                                    @endphp
                                </ul>
                            </div>
                            @if ($i < count($permisosOrdenados))
                                <div class="col-md-4 pr-1 pl-1">
                                    <ul class="list-group list-group-sm">
                                        <li class="list-group-item list-group-item-dark">
                                            <label class="">
                                                <input type="checkbox" name="modelo" value="0" id="{{ $permisosOrdenados[$i][0] }}" onchange="marcarTodo(this.id);">
                                                {{$permisosOrdenados[$i][0]}}
                                            </label>
                                        </li>
                                        @for ($j = 1; $j < count($permisosOrdenados[$i]); $j+=2)
                                            <li class="list-group-item">
                                                <input type="checkbox" name="permissions[]" value="{{ $permisosOrdenados[$i][$j] }}" class="{{ $permisosOrdenados[$i][0] }}" {{ $role->hasPermissionTo($permisosOrdenados[$i][$j]) ? 'checked' : '' }}>
                                                {{$permisosOrdenados[$i][$j + 1]}}
                                            </li>
                                        @endfor
                                        @php
                                            $i++;
                                        @endphp
                                    </ul>
                                </div>
                                @if ($i < count($permisosOrdenados))
                                    <div class="col-md-4 pl-1">
                                        <ul class="list-group list-group-sm">
                                            <li class="list-group-item list-group-item-dark">
                                                <label class="">
                                                    <input type="checkbox" name="modelo" value="0" id="{{ $permisosOrdenados[$i][0] }}" onchange="marcarTodo(this.id);">
                                                    {{$permisosOrdenados[$i][0]}}
                                                </label>
                                            </li>
                                            @for ($j = 1; $j < count($permisosOrdenados[$i]); $j+=2)
                                                <li class="list-group-item">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permisosOrdenados[$i][$j] }}" class="{{ $permisosOrdenados[$i][0] }}" {{ $role->hasPermissionTo($permisosOrdenados[$i][$j]) ? 'checked' : '' }}>
                                                    {{$permisosOrdenados[$i][$j + 1]}}
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <hr>
                    @endfor
                </div>
            </form>
        </div>
    </div>
@endif
