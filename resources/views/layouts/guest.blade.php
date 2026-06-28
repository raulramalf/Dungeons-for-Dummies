<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
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
            font-family: 'Georgia', serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: #120309;
            border: 1px solid rgba(179, 3, 3, 0.3);
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-logo span {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .auth-logo .subtitulo {
            display: block;
            color: var(--color-rojo);
            font-size: 0.75rem;
            letter-spacing: 4px;
            margin-top: 4px;
        }

        /* Inputs de Breeze */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: #20050E;
            border: 1px solid rgba(179, 3, 3, 0.3);
            border-radius: 8px;
            padding: 12px 15px;
            color: white;
            font-family: 'Georgia', serif;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        input[type="checkbox"] {
            width: 16px !important;
            height: 16px !important;
            accent-color: var(--color-rojo);
            cursor: pointer;
            margin: 0;
            flex-shrink: 0;
        }

        input:focus {
            outline: none;
            border-color: var(--color-rojo);
        }

        label {
            color: var(--color-gris);
            font-size: 0.8rem;
        }

        /* Botón primario */
        button[type="submit"],
        .btn-primary {
            background: var(--color-rojo);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Georgia', serif;
            font-size: 0.95rem;
            letter-spacing: 1px;
            transition: background 0.2s;
        }

        button[type="submit"]:hover {
            background: #8f0202;
        }

        /* Links */
        a {
            color: var(--color-gris);
            font-size: 0.85rem;
            text-decoration: underline;
        }

        a:hover {
            color: white;
        }

        /* Errores */
        .text-red-600, [class*="text-red"] {
            color: var(--color-naranja) !important;
            font-size: 0.8rem;
            margin-top: 4px;
            display: block;
        }

        /* Checkbox */
        input[type="checkbox"] {
            width: auto;
            accent-color: var(--color-rojo);
        }

        .mt-4 { margin-top: 16px; }
        .mt-6 { margin-top: 24px; }
        .mt-2 { margin-top: 8px; }
        .ms-2 { margin-left: 8px; }
        .ms-3 { margin-left: 12px; }
        .ms-4 { margin-left: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-end { justify-content: flex-end; }
        .justify-between { justify-content: space-between; }
        .block { display: block; }
        .w-full { width: 100%; }
        .inline-flex { display: inline-flex; }
        .text-sm { font-size: 0.85rem; }
        .text-green-600 { color: #4caf50; font-size: 0.85rem; }
        .font-medium { font-weight: bold; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <a href="/" style="text-decoration: none;">
                <span>⚔ Dungeons for Dummies</span>
                <span class="subtitulo">Tu compañero de aventuras</span>
            </a>
        </div>
        {{ $slot }}
    </div>
</body>
</html>