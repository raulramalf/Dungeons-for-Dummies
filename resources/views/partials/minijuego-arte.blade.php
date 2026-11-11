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
    @case('dragon')
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs><radialGradient id="g_dragon" cx="50%" cy="42%" r="65%"><stop offset="0%" stop-color="#3a0a0a"/><stop offset="100%" stop-color="#120309"/></radialGradient></defs>
            <circle cx="100" cy="100" r="96" fill="url(#g_dragon)"/>
            <circle cx="100" cy="100" r="96" fill="none" stroke="#8a6b1f" stroke-opacity=".5" stroke-width="2"/>
            <path d="M30 70L12 30l44 22z" fill="#7a1010"/><path d="M170 70l18-40-44 22z" fill="#7a1010"/>
            <path d="M70 48L60 14l24 22zM130 48l10-34-24 22z" fill="#e8d9b0"/>
            <path d="M100 40c-34 0-52 26-52 56 0 20 8 34 20 44l-6 22 38-12 38 12-6-22c12-10 20-24 20-44 0-30-18-56-52-56z" fill="#b30303"/>
            <path d="M100 40c-34 0-52 26-52 56 0 20 8 34 20 44l-6 22 38-12V40z" fill="#8a0202" opacity=".6"/>
            <path d="M62 84l22 8-22 4zM138 84l-22 8 22 4z" fill="#f5c04a"/>
            <ellipse cx="73" cy="90" rx="3" ry="6" fill="#120309"/><ellipse cx="127" cy="90" rx="3" ry="6" fill="#120309"/>
            <circle cx="88" cy="128" r="3.5" fill="#120309"/><circle cx="112" cy="128" r="3.5" fill="#120309"/>
            <path d="M74 144c8 6 44 6 52 0" stroke="#120309" stroke-width="4" fill="none" stroke-linecap="round"/>
            <path d="M82 144l3 9 5-8zM110 145l5 8 3-9z" fill="#f2e9e4"/>
            <path d="M92 156c4 10 12 10 16 0" fill="#ff8a1f" opacity=".85"/>
        </svg>
        @break

    @case('liche')
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs><radialGradient id="g_liche" cx="50%" cy="42%" r="65%"><stop offset="0%" stop-color="#1d0f33"/><stop offset="100%" stop-color="#120309"/></radialGradient></defs>
            <circle cx="100" cy="100" r="96" fill="url(#g_liche)"/>
            <circle cx="100" cy="100" r="96" fill="none" stroke="#8a6b1f" stroke-opacity=".5" stroke-width="2"/>
            <path d="M40 190c0-50 18-80 60-80s60 30 60 80z" fill="#2a1450"/>
            <path d="M56 70c0-34 20-52 44-52s44 18 44 52v16H56z" fill="#3b1d6e"/>
            <path d="M68 70c0-24 14-38 32-38s32 14 32 38v36c0 16-14 30-32 30s-32-14-32-30z" fill="#e8dfc8"/>
            <ellipse cx="85" cy="82" rx="9" ry="11" fill="#120309"/><ellipse cx="115" cy="82" rx="9" ry="11" fill="#120309"/>
            <circle cx="85" cy="84" r="3.5" fill="#6be3ff"/><circle cx="115" cy="84" r="3.5" fill="#6be3ff"/>
            <path d="M96 98l4 8 4-8z" fill="#120309"/>
            <path d="M82 118h36" stroke="#120309" stroke-width="3"/><path d="M88 114v8M96 114v8M104 114v8M112 114v8" stroke="#120309" stroke-width="2"/>
            <path d="M82 28l8-16 10 12 10-12 8 16z" fill="#d4a62a"/><circle cx="100" cy="22" r="3" fill="#6be3ff"/>
        </svg>
        @break

    @case('mimico')
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs><radialGradient id="g_mimico" cx="50%" cy="42%" r="65%"><stop offset="0%" stop-color="#35210a"/><stop offset="100%" stop-color="#120309"/></radialGradient></defs>
            <circle cx="100" cy="100" r="96" fill="url(#g_mimico)"/>
            <circle cx="100" cy="100" r="96" fill="none" stroke="#8a6b1f" stroke-opacity=".5" stroke-width="2"/>
            <rect x="34" y="82" width="132" height="84" rx="8" fill="#7a4a1a"/>
            <path d="M34 90c0-34 132-34 132 0v-8c0-30-132-30-132 0z" fill="#5c3510"/>
            <path d="M34 82h132" stroke="#d4a62a" stroke-width="5"/><path d="M34 120h132" stroke="#d4a62a" stroke-width="3" opacity=".6"/>
            <rect x="90" y="108" width="20" height="24" rx="3" fill="#d4a62a"/><circle cx="100" cy="120" r="3" fill="#120309"/>
            <path d="M40 84l8 14 8-14 8 14 8-14 8 14 8-14 8 14 8-14 8 14 8-14 8 14 8-14 8 14 8-14 8 14z" fill="#f2e9e4"/>
            <path d="M98 100c8 26 20 38 34 40-12-6-16-20-16-36z" fill="#d6455d"/>
            <circle cx="74" cy="64" r="8" fill="#e3c25a"/><circle cx="126" cy="64" r="8" fill="#e3c25a"/>
            <circle cx="74" cy="64" r="3" fill="#120309"/><circle cx="126" cy="64" r="3" fill="#120309"/>
        </svg>
        @break

    @case('troll')
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs><radialGradient id="g_troll" cx="50%" cy="42%" r="65%"><stop offset="0%" stop-color="#102812"/><stop offset="100%" stop-color="#120309"/></radialGradient></defs>
            <circle cx="100" cy="100" r="96" fill="url(#g_troll)"/>
            <circle cx="100" cy="100" r="96" fill="none" stroke="#8a6b1f" stroke-opacity=".5" stroke-width="2"/>
            <path d="M40 150c0-60 22-102 60-102s60 42 60 102z" fill="#4d7a3c"/>
            <path d="M40 150c0-60 22-102 60-102v102z" fill="#3b5e2e" opacity=".6"/>
            <path d="M50 78L28 62l12 28zM150 78l22-16-12 28z" fill="#4d7a3c"/>
            <path d="M66 82l24 6M134 82l-24 6" stroke="#120309" stroke-width="5" stroke-linecap="round"/>
            <circle cx="80" cy="96" r="6" fill="#f0e060"/><circle cx="120" cy="96" r="6" fill="#f0e060"/>
            <circle cx="80" cy="96" r="2.5" fill="#120309"/><circle cx="120" cy="96" r="2.5" fill="#120309"/>
            <path d="M92 104c0 14-8 16-8 22h32c0-6-8-8-8-22z" fill="#3b5e2e"/>
            <path d="M68 138c14 10 50 10 64 0v14H68z" fill="#120309"/>
            <path d="M74 138v-10l7 10zM126 138v-10l-7 10zM96 140l4-8 4 8z" fill="#f2e9e4"/>
            <circle cx="62" cy="66" r="4" fill="#3b5e2e"/><circle cx="140" cy="60" r="3" fill="#3b5e2e"/>
        </svg>
        @break

@endswitch
