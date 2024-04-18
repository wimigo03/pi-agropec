<div class="profile">
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @if (isset($empresa))
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <img src="{{ url($empresa->url_logo) }}" alt="{{ $empresa->url_logo }}" class="img-fluid">
        </a>
        <h3 class="name">{{ $empresa->nombre_comercial }}</h3>
        <span class="country">{{ auth()->user()->cargo_header }}</span>
    @else
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <img src="/images/pi-agropec.jpg" alt="pi-resto" class="img-fluid">
        </a>
        <br>
        <span class="country">{{ auth()->user()->cargo_header }}</span>
    @endif
</div>
