@canany(['comprobante.editar','comprobante.pdf','comprobante.show'])
    <td class="text-center p-1">
        <div class="d-flex justify-content-center">
            @can('comprobante.pdf')
                <span class="tts:left tts-slideIn tts-custom" aria-label="Pdf" style="cursor: pointer;">
                    <a href="{{ route('comprobante.pdf',$comprobante_id) }}" class="badge-with-padding badge badge-danger" target="_blank">
                        <i class="fa-solid fa-file-pdf fa-fw"></i>
                    </a>
                </span>
                &nbsp;
            @endcan
            @can('comprobante.show')
                <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a detalle" style="cursor: pointer;">
                    <a href="{{ route('comprobante.show',$comprobante_id) }}" class="badge-with-padding badge badge-primary">
                        <i class="fa-solid fa-list-check fa-fw"></i>
                    </a>
                </span>
                &nbsp;
            @endcan
            @if ($estado == '1')
                @can('comprobante.editar')
                    <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                        <a href="{{ route('comprobante.editar',$comprobante_id) }}" class="badge-with-padding badge badge-warning">
                            <i class="fas fa-edit fa-fw"></i>
                        </a>
                    </span>
                @endcan
                &nbsp;
            @else
                <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar No Permitido" style="cursor: pointer;">
                    <a href="#" class="badge-with-padding badge badge-secondary">
                        <i class="fas fa-edit fa-fw"></i>
                    </a>
                </span>
                &nbsp;
            @endif
        </div>
    </td>
@endcanany
