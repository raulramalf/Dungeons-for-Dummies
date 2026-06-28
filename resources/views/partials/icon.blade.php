{{--
    LIBRERÍA DE ICONOS SVG — Dungeons for Dummies
    Uso:  @include('partials.icon', ['name' => 'sword'])
          @include('partials.icon', ['name' => 'sword', 'class' => 'icon-lg'])

    Iconos disponibles:
    home, sword, swords, scroll, skull, user, feed, logout, eye, edit, trash,
    plus, heart, comment, shield, dice, dragon, coins, chevron-left,
    chevron-right, book, flame, star, helmet, potion, bow, settings, lock,
    arrow-left, x, check, image, map
--}}
@php $cls = $class ?? ''; @endphp

@switch($name)
    @case('home')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M3 11l9-8 9 8"/><path d="M5 10v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V10"/><path d="M9 21v-6h6v6"/></svg>
        @break
    @case('sword')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M14.5 17.5L3 6V3h3l11.5 11.5"/><path d="M13 19l6-6"/><path d="M16 16l4 4"/><path d="M19 21l2-2"/></svg>
        @break
    @case('swords')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M14.5 17.5L3 6V3h3l11.5 11.5"/><path d="M13 19l6-6"/><path d="M16 16l4 4"/><path d="M9.5 6.5L21 18v3h-3L6.5 9.5"/><path d="M5 13l6 6"/><path d="M8 16l-4 4"/></svg>
        @break
    @case('scroll')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 0 0-2 2v3"/><path d="M3 8h6"/><path d="M19 21h-9a2 2 0 0 1-2-2V6a3 3 0 0 0-3-3"/><path d="M16 3h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2"/><path d="M12 9h5"/><path d="M12 13h5"/></svg>
        @break
    @case('skull')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><circle cx="9" cy="12" r="1.2"/><circle cx="15" cy="12" r="1.2"/><path d="M8 20v-2a2 2 0 0 0-1-1.7A7 7 0 1 1 19 10.2 7 7 0 0 1 17 16.3a2 2 0 0 0-1 1.7v2a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1z"/><path d="M11 20v2"/><path d="M13 20v2"/></svg>
        @break
    @case('user')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/></svg>
        @break
    @case('feed')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="M8 8h8"/><path d="M8 12h8"/><path d="M8 16h5"/></svg>
        @break
    @case('logout')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
        @break
    @case('eye')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
        @break
    @case('edit')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
        @break
    @case('trash')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
        @break
    @case('plus')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        @break
    @case('heart')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>
        @break
    @case('comment')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.5 8.5 0 0 1-4-1L3 20l1.1-4A8.4 8.4 0 0 1 12 3a8.4 8.4 0 0 1 9 8.5z"/></svg>
        @break
    @case('shield')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M12 2l8 3v6c0 5-3.4 8.5-8 11-4.6-2.5-8-6-8-11V5z"/></svg>
        @break
    @case('dice')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M12 2l9 5v10l-9 5-9-5V7z"/><path d="M12 2v20"/><path d="M3 7l9 5 9-5"/></svg>
        @break
    @case('dragon')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M16 3l4 2-2 3 3 2-4 2 1 4-4-2-2 4-2-4-4 2 1-4-4-2 3-2-2-3 4-2 3 3z"/><circle cx="12" cy="11" r="1.5"/></svg>
        @break
    @case('coins')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><circle cx="9" cy="9" r="6"/><path d="M16.5 4.2a6 6 0 0 1 0 11.6"/><path d="M9 6v6"/><path d="M7 8h4"/></svg>
        @break
    @case('chevron-left')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
        @break
    @case('chevron-right')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        @break
    @case('book')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        @break
    @case('flame')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M12 2c1 4 5 5 5 9a5 5 0 0 1-10 0c0-2 1-3 1-3 1 2 2 2 2 2-1-3 1-6 2-8z"/></svg>
        @break
    @case('star')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M12 2l3 7h7l-5.5 4.5L18.5 22 12 17.5 5.5 22l2-8.5L2 9h7z"/></svg>
        @break
    @case('helmet')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M4 14a8 8 0 0 1 16 0v3a2 2 0 0 1-2 2h-3v-4H9v4H6a2 2 0 0 1-2-2z"/><path d="M12 6v8"/></svg>
        @break
    @case('potion')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M9 2h6"/><path d="M10 2v6L5.5 16a3.5 3.5 0 0 0 3 5h7a3.5 3.5 0 0 0 3-5L14 8V2"/><path d="M7 15h10"/></svg>
        @break
    @case('settings')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19 12a7 7 0 0 0-.1-1.4l2-1.6-2-3.4-2.4 1a7 7 0 0 0-2.4-1.4L13.7 2h-3.4l-.4 2.6A7 7 0 0 0 7.5 6L5 5 3 8.4l2 1.6a7 7 0 0 0 0 2.8l-2 1.6 2 3.4 2.5-1a7 7 0 0 0 2.4 1.4l.4 2.8h3.4l.4-2.6a7 7 0 0 0 2.4-1.4l2.4 1 2-3.4-2-1.6c.1-.5.1-.9.1-1.4z"/></svg>
        @break
    @case('lock')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
        @break
    @case('arrow-left')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
        @break
    @case('x')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        @break
    @case('check')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
        @break
    @case('image')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
        @break
    @case('map')
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><path d="M9 4L3 6v14l6-2 6 2 6-2V4l-6 2-6-2z"/><path d="M9 4v14"/><path d="M15 6v14"/></svg>
        @break
    @default
        <svg class="icon {{ $cls }}" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/></svg>
@endswitch