{{-- Campos de una sesión (nueva o editar). $p = prefijo de ids ("nueva" | "edit"); $numero = nº sugerido (solo nueva). --}}
<div class="campos-grid">
    <div class="campo">
        <label for="{{ $p }}-numero">Número <span class="req">*</span></label>
        <input type="number" name="numero_sesion" id="{{ $p }}-numero" class="control" min="1" inputmode="numeric" required @isset($numero) value="{{ $numero }}" @endisset>
    </div>
    <div class="campo">
        <label for="{{ $p }}-fecha">Fecha</label>
        <input type="date" name="fecha_sesion" id="{{ $p }}-fecha" class="control">
    </div>
</div>
<div class="campo">
    <label for="{{ $p }}-titulo">Título <span class="req">*</span></label>
    <input type="text" name="titulo" id="{{ $p }}-titulo" class="control" required maxlength="255">
</div>
<div class="campo">
    <label for="{{ $p }}-resumen">Resumen</label>
    <textarea name="resumen" id="{{ $p }}-resumen" class="control" rows="4" placeholder="¿Qué pasó en esta sesión?"></textarea>
</div>
<div class="campo">
    <label for="{{ $p }}-duracion">Duración (minutos)</label>
    <input type="number" name="duracion_minutos" id="{{ $p }}-duracion" class="control" min="0" inputmode="numeric">
</div>
