<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dungeons for Dummies</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-fondo: #20050E;
            --color-rojo: #B30303;
            --color-gris: #768596;
            --color-verde: #404834;
            --color-naranja: #D46043;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--color-fondo);
            color: #ffffff;
            font-family: 'Georgia', serif;
            display: flex;
            min-height: 100vh;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 99;
        }
        .overlay.active { display: block; }

        .sidebar {
            width: 220px;
            background-color: #120309;
            display: flex;
            flex-direction: column;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar .logo {
            text-align: center;
            font-size: 1.1rem;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 2px;
            padding: 20px;
            border-bottom: 1px solid var(--color-rojo);
            margin-bottom: 20px;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            color: var(--color-gris);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background-color: rgba(179, 3, 3, 0.15);
            color: #ffffff;
            border-left: 3px solid var(--color-rojo);
        }

        .hamburger {
            display: none;
            background: var(--color-rojo);
            border: none;
            border-radius: 8px;
            width: 42px;
            height: 42px;
            cursor: pointer;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            flex-shrink: 0;
        }

        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .main-content {
            margin-left: 220px;
            flex: 1;
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(179, 3, 3, 0.3);
        }

        .page-header h1 {
            font-size: 1.5rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .topbar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background-color: #120309;
            border-bottom: 1px solid var(--color-rojo);
            align-items: center;
            gap: 15px;
            padding: 0 15px;
            z-index: 150;
        }

        .topbar-titulo {
            font-size: 1rem;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar .logo {
                padding-top: 70px;
            }

            .hamburger {
                display: flex;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
                padding-top: 70px;
            }

            .topbar {
                display: flex;
            }

            .page-header {
                display: none;
            }

            .hamburger {
                position: static;
            }
        }

        @media (max-width: 600px) {
            .main-content {
                padding: 15px;
                padding-top: 70px;
            }

            .page-header h1 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

    <div class="overlay" id="overlay" onclick="cerrarMenu()"></div>

    <header class="topbar">
        <button class="hamburger" id="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <span class="topbar-titulo">@yield('titulo')</span>
    </header>

    <aside class="sidebar" id="sidebar">
        <div class="logo">⚔ DUNGEONS<br>FOR DUMMIES</div>
        <nav>
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">🏠 Inicio</a>
            <a href="/feed" class="{{ request()->is('feed') ? 'active' : '' }}">📰 Feed</a>
            <a href="/personajes" class="{{ request()->is('personajes') ? 'active' : '' }}">⚔ Personajes</a>
            <a href="/campanyas" class="{{ request()->is('campanyas') ? 'active' : '' }}">📜 Campañas</a>
            <a href="/enemigos" class="{{ request()->is('enemigos') ? 'active' : '' }}">💀 Enemigos</a>
            <a href="/perfil" class="{{ request()->is('perfil') ? 'active' : '' }}">👤 Perfil</a>
        </nav>
        <div style="margin-top: auto; padding: 20px; border-top: 1px solid rgba(179,3,3,0.2);">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background: none; border: none; color: var(--color-gris); cursor: pointer; font-family: Georgia, serif; font-size: 0.9rem; width: 100%; text-align: left; padding: 5px 0;">
                    🚪 Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1>@yield('titulo')</h1>
        </div>
        @yield('contenido')
    </main>

    <script>
        function toggleMenu() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('active');
        }

        function cerrarMenu() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('active');
        }

        document.querySelectorAll('.sidebar nav a').forEach(a => {
            a.addEventListener('click', cerrarMenu);
        });
    </script>

</body>
</html>