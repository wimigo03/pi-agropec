<div class="row">
    <div class="col-md-7 px-1 pr-1 font-roboto-15" style="display: flex; align-items: flex-end;">
        <b>
            {{ $header }}
            @if (isset($empresa))
                - {{ $empresa->nombre_comercial }}
            @endif
        </b>
    </div>
    {{--<div class="col-md-2 pr-1 pl-1 text-center">
        <span class="btns btn btn-sm btn-outline-dark font-verdana" id="toggleSubMenu" style="cursor: pointer;">
            <i class="{{ $icono }} fa-beat"></i>
        </span>
    </div>--}}
    <div class="col-md-5 px-1 pl-1 font-roboto-14" style="display: flex; justify-content: flex-end; align-items: flex-end;">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <div class="dropdown">
            <span class="">
                <i class="fa-solid fa-user fa-fw"></i>
                {{ Auth::user()->username }} - {{ ucwords(strtolower(Auth::user()->cargo_header)) }}
                <i class="fa-solid fa-caret-down fa-fw"></i>
            </span>
            <div class="dropdown-content right">
                <a href="#">
                    <i class="fa-solid fa-id-badge fa-fw"></i> Mi Perfil
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket fa-fw"></i> Salir
                </a>
            </div>
        </div>
    </div>
</div>
<hr class="custom-hr">
