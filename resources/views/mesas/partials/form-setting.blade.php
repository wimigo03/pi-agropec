<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="pi_cliente_id" value="{{ $empresa->pi_cliente_id }}" id="pi_cliente_id">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
    <div class="form-group row font-roboto-12">
        <div class="col-md-4 hiddenContent" id="datos-mesa" style="display: none;">
            x
        </div>
        <div class="col-md-12 px-1" id="mapa-mesa">
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                @foreach($zonas as $key => $zona)
                    <li class="nav-item">
                        <a class="nav-link {{ $key === 0 ? 'active' : '' }}" id="tab{{ $key + 1 }}" data-toggle="tab" href="#content{{ $key + 1 }}" role="tab" aria-controls="content{{ $key + 1 }}" aria-selected="{{ $key === 0 ? 'true' : 'false' }}" data-zona-id="{{ $zona->id }}">
                                {{ $zona->nombre }}
                                <input type="hidden" name="zona_id" value="#" id="zona_id">
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content" id="myTabsContent">
                <br>
                @foreach($zonas as $key => $zona)
                    <div class="tab-pane fade {{ $key === 0 ? 'show active' : '' }}" id="content{{ $key + 1 }}" role="tabpanel" aria-labelledby="tab{{ $key + 1 }}">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div id="tablaContainer">
                                    <table class="display table-bordered responsive" id="table-zona">
                                        @for ($i = 0; $i < $zona->filas; $i++)
                                            <tr class="font-roboto">
                                                @for ($j = 0; $j < $zona->columnas; $j++)
                                                    <td class="text-center p-1 cell-with-image">
                                                        <span onclick="alternarDatos()" style="cursor: pointer;">
                                                        @php
                                                            $ocupado = App\Models\Mesa::where('zona_id',$zona->id)->where('fila',$i)->where('columna',$j)->where('estado','3')->first();
                                                        @endphp
                                                        @if ($ocupado != null)
                                                            <div class="imagen-con-texto">
                                                                <img src="/images/mesa-1.png" alt="mesa-1" class="imagen-mesa-1">
                                                                <div class="circulo-texto"></div>
                                                                <span class="texto-sobre-imagen font-roboto"><b>{{ $ocupado->numero }}</b></span>
                                                            </div>
                                                        @else
                                                            @if ($zona->mesas_habilitadas != 0)
                                                                <span class="tts:down tts-slideIn tts-custom" aria-label="Insertar Mesa" style="cursor: pointer;">
                                                                    <span class="badge-with-padding badge badge-secondary text-white" data-toggle="modal" data-target="#ModalAsignarMesa" style="opacity: 0.5;" onclick="cargarMesas('{{ $i }}','{{ $j }}');">
                                                                        <i class="fa-solid fa-plus fa-fw"></i>
                                                                    </span>
                                                                </span>
                                                            @endif
                                                        @endif
                                                        </span>
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</form>
