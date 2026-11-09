{{-- Campos de una nota del DM (nueva o editar). $p = prefijo de ids ("nueva-nota" | "edit-nota"). --}}
<div class="campo">
    <label for="{{ $p }}-titulo">Título <span class="req">*</span></label>
    <input type="text" name="titulo" id="{{ $p }}-titulo" class="control" required maxlength="255">
</div>
<div class="campo">
    <label for="{{ $p }}-contenido">Contenido <span class="req">*</span></label>
    <textarea name="contenido" id="{{ $p }}-contenido" class="control" rows="6" required></textarea>
</div>
<label class="check-inline" for="{{ $p }}-visible">
    <input type="checkbox" name="visible_jugadores" id="{{ $p }}-visible" @checked($visibleDefecto ?? false)>
    Visible para los jugadores
</label>
