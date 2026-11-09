@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'estado-ok', 'role' => 'status']) }}>
        {{ $status }}
    </div>
@endif
