<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <span class="marca">Dungeons</span>
        <span class="sub">for Dummies</span>
    </div>

    <nav>
        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
            @include('partials.icon', ['name' => 'home']) Inicio
        </a>
        <a href="{{ url('/feed') }}" class="{{ request()->is('feed') ? 'active' : '' }}">
            @include('partials.icon', ['name' => 'feed']) Taberna
        </a>
        <a href="{{ url('/personajes') }}" class="{{ request()->is('personajes*') ? 'active' : '' }}">
            @include('partials.icon', ['name' => 'sword']) Personajes
        </a>
        <a href="{{ url('/campanyas') }}" class="{{ request()->is('campanyas*') ? 'active' : '' }}">
            @include('partials.icon', ['name' => 'scroll']) Campañas
        </a>
        <a href="{{ url('/enemigos') }}" class="{{ request()->is('enemigos*') ? 'active' : '' }}">
            @include('partials.icon', ['name' => 'skull']) Bestiario
        </a>
        <a href="{{ url('/perfil') }}" class="{{ request()->is('perfil*') ? 'active' : '' }}">
            @include('partials.icon', ['name' => 'user']) Perfil
        </a>
    </nav>

    @auth
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">
                @include('partials.icon', ['name' => 'logout']) Cerrar sesión
            </button>
        </form>
    </div>
    @endauth
</aside>