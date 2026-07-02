{{--
    ARTE DE ENEMIGOS — Minijuego Diario
    Uso: @include('partials.minijuego-arte', ['arte' => 'goblin'])
--}}
@switch($arte ?? null)

    @case('goblin')
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="gGoblinBg" cx="50%" cy="42%" r="65%">
                    <stop offset="0%" stop-color="#2e0c13"/>
                    <stop offset="100%" stop-color="#120309"/>
                </radialGradient>
            </defs>
            <circle cx="100" cy="100" r="96" fill="url(#gGoblinBg)"/>
            <circle cx="100" cy="100" r="96" fill="none" stroke="#8a6b1f" stroke-opacity=".5" stroke-width="2"/>
            <path d="M55 95 L20 60 L48 118 Z" fill="#5a6b3f"/>
            <path d="M145 95 L180 60 L152 118 Z" fill="#5a6b3f"/>
            <path d="M100 55c-30 0-46 24-46 50 0 30 20 48 46 48s46-18 46-48c0-26-16-50-46-50z" fill="#6c8248"/>
            <path d="M100 55c-30 0-46 24-46 50 0 30 20 48 46 48v-98z" fill="#5a6b3f" opacity=".55"/>
            <path d="M64 100c8-8 20-8 26-2" stroke="#2e0c13" stroke-width="5" fill="none" stroke-linecap="round"/>
            <path d="M136 100c-8-8-20-8-26-2" stroke="#2e0c13" stroke-width="5" fill="none" stroke-linecap="round"/>
            <circle cx="80" cy="112" r="7" fill="#e3c25a"/>
            <circle cx="120" cy="112" r="7" fill="#e3c25a"/>
            <circle cx="80" cy="112" r="3" fill="#1a0509"/>
            <circle cx="120" cy="112" r="3" fill="#1a0509"/>
            <path d="M94 122c2 5 4 8 6 8s4-3 6-8" stroke="#3d4a2c" stroke-width="3" fill="none" stroke-linecap="round"/>
            <path d="M78 138c10 10 34 10 44 0" stroke="#2e0c13" stroke-width="4" fill="none" stroke-linecap="round"/>
            <path d="M86 138l-3 10 8-4z" fill="#f2e9e4"/>
            <path d="M114 138l3 10-8-4z" fill="#f2e9e4"/>
        </svg>
        @break

    @case('esqueleto')
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="gEsqBg" cx="50%" cy="42%" r="65%">
                    <stop offset="0%" stop-color="#25090f"/>
                    <stop offset="100%" stop-color="#120309"/>
                </radialGradient>
            </defs>
            <circle cx="100" cy="100" r="96" fill="url(#gEsqBg)"/>
            <circle cx="100" cy="100" r="96" fill="none" stroke="#b30303" stroke-opacity=".35" stroke-width="2"/>
            <path d="M100 48c-28 0-44 20-44 44 0 16 6 26 12 33v13c0 6 5 10 10 10h44c5 0 10-4 10-10v-13c6-7 12-17 12-33 0-24-16-44-44-44z" fill="#e9e0d6"/>
            <path d="M100 48c-28 0-44 20-44 44 0 16 6 26 12 33v13c0 6 5 10 10 10h22V48z" fill="#c9c0b4" opacity=".55"/>
            <path d="M64 100a14 14 0 1 0 28 0 14 14 0 0 0-28 0z" fill="#1a0509"/>
            <path d="M108 100a14 14 0 1 0 28 0 14 14 0 0 0-28 0z" fill="#1a0509"/>
            <circle cx="78" cy="100" r="4" fill="#b30303"/>
            <circle cx="122" cy="100" r="4" fill="#b30303"/>
            <path d="M100 112l-7 12h14z" fill="#1a0509"/>
            <g stroke="#1a0509" stroke-width="2.5">
                <path d="M76 140h48"/>
                <path d="M84 140v10"/>
                <path d="M94 140v10"/>
                <path d="M104 140v10"/>
                <path d="M114 140v10"/>
            </g>
            <path d="M70 78l8 10-6 8" stroke="#a89a92" stroke-width="1.5" fill="none"/>
            <path d="M132 84l-6 8 8 6" stroke="#a89a92" stroke-width="1.5" fill="none"/>
        </svg>
        @break

    @case('lobo')
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="gLoboBg" cx="50%" cy="42%" r="65%">
                    <stop offset="0%" stop-color="#1e0a12"/>
                    <stop offset="100%" stop-color="#100208"/>
                </radialGradient>
            </defs>
            <circle cx="100" cy="100" r="96" fill="url(#gLoboBg)"/>
            <circle cx="100" cy="100" r="96" fill="none" stroke="#6e635d" stroke-opacity=".4" stroke-width="2"/>
            <path d="M62 78 L48 40 L80 66 Z" fill="#3a2230"/>
            <path d="M138 78 L152 40 L120 66 Z" fill="#3a2230"/>
            <path d="M100 60c-32 0-50 22-50 46 0 20 10 34 20 42l6 14c1 3 4 5 7 5h34c3 0 6-2 7-5l6-14c10-8 20-22 20-42 0-24-18-46-50-46z" fill="#4a2d3d"/>
            <path d="M100 60c-32 0-50 22-50 46 0 20 10 34 20 42l6 14c1 3 4 5 7 5h17V60z" fill="#3a2230" opacity=".6"/>
            <path d="M78 118c6 14 14 20 22 20s16-6 22-20c-6 8-14 12-22 12s-16-4-22-12z" fill="#2a1622"/>
            <path d="M100 130l-6 10h12z" fill="#100208"/>
            <path d="M74 96c4-6 12-8 18-4" stroke="#d44141" stroke-width="4" fill="none" stroke-linecap="round"/>
            <path d="M126 96c-4-6-12-8-18-4" stroke="#d44141" stroke-width="4" fill="none" stroke-linecap="round"/>
            <circle cx="82" cy="100" r="4" fill="#e3c25a"/>
            <circle cx="118" cy="100" r="4" fill="#e3c25a"/>
        </svg>
        @break

    @default
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <circle cx="100" cy="100" r="96" fill="#2e0c13"/>
            <path d="M100 55c-30 0-46 24-46 50 0 30 20 48 46 48s46-18 46-48c0-26-16-50-46-50z" fill="#b30303" opacity=".4"/>
        </svg>
@endswitch
