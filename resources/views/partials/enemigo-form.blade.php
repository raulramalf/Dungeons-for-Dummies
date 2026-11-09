{{--
    Campos del formulario de enemigo, compartidos por "Nuevo" y "Editar".
    Parámetros: $p      prefijo de los ids ("nuevo" | "ver"; js/pages/enemigos.js rellena "ver-*")
                $nuevo  true → valores por defecto; false → los rellena el JS desde /enemigos/{id}
--}}
@php
    $v = fn (string $campo, $defecto = '') => $nuevo ? old($campo, $defecto) : '';
    $tamanos = ['Diminuto', 'Pequeño', 'Mediano', 'Grande', 'Enorme', 'Gargantuesco'];
@endphp

<div class="campos-grid">
    <div class="campo campo--completo">
        <div class="campo-cab">
            <label for="{{ $p }}-nombre">Nombre <span class="req">*</span></label>
            <label class="check-inline" for="{{ $p }}-boss">
                <input type="checkbox" name="es_boss" id="{{ $p }}-boss" @checked($nuevo && old('es_boss'))>
                Es un boss
            </label>
        </div>
        <input type="text" name="nombre" id="{{ $p }}-nombre" class="control" value="{{ $v('nombre') }}" required maxlength="255">
    </div>

    <div class="campo">
        <label for="{{ $p }}-tipo">Tipo <span class="req">*</span></label>
        <input type="text" name="tipo" id="{{ $p }}-tipo" class="control" value="{{ $v('tipo') }}" placeholder="Humanoide, Bestia…" required>
    </div>
    <div class="campo">
        <label for="{{ $p }}-tamaño">Tamaño <span class="req">*</span></label>
        <select name="tamaño" id="{{ $p }}-tamaño" class="control" required>
            @foreach ($tamanos as $t)
                <option value="{{ $t }}" @selected($nuevo && old('tamaño', 'Mediano') === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="campo">
        <label for="{{ $p }}-alineamiento">Alineamiento</label>
        <input type="text" name="alineamiento" id="{{ $p }}-alineamiento" class="control" value="{{ $v('alineamiento') }}" placeholder="Caótico malvado…">
    </div>
    <div class="campo">
        <label for="{{ $p }}-cr">CR (clase de desafío) <span class="req">*</span></label>
        <input type="number" name="clase_de_desafio" id="{{ $p }}-cr" class="control" value="{{ $v('clase_de_desafio', 1) }}" step="0.125" min="0" inputmode="decimal" required>
    </div>
    <div class="campo">
        <label for="{{ $p }}-xp">Puntos de experiencia <span class="req">*</span></label>
        <input type="number" name="puntos_de_experiencia" id="{{ $p }}-xp" class="control" value="{{ $v('puntos_de_experiencia', 0) }}" inputmode="numeric" required>
    </div>
</div>

<h3 class="form-subtitulo">Combate</h3>
<div class="campos-grid campos-grid--3">
    <div class="campo">
        <label for="{{ $p }}-ca">CA <span class="req">*</span></label>
        <input type="number" name="clase_de_armadura" id="{{ $p }}-ca" class="control" value="{{ $v('clase_de_armadura', 10) }}" inputmode="numeric" required>
    </div>
    <div class="campo">
        <label for="{{ $p }}-tipo-armadura">Tipo de armadura</label>
        <input type="text" name="tipo_armadura" id="{{ $p }}-tipo-armadura" class="control" value="{{ $v('tipo_armadura') }}" placeholder="Armadura natural…">
    </div>
    <div class="campo">
        <label for="{{ $p }}-pg">Puntos de golpe <span class="req">*</span></label>
        <input type="text" name="puntos_de_golpe" id="{{ $p }}-pg" class="control" value="{{ $v('puntos_de_golpe') }}" placeholder="66 (12d8+12)" required>
    </div>
    <div class="campo campo--completo">
        <label for="{{ $p }}-velocidad">Velocidad <span class="req">*</span></label>
        <input type="text" name="velocidad" id="{{ $p }}-velocidad" class="control" value="{{ $v('velocidad', '9 m.') }}" required>
    </div>
</div>

<h3 class="form-subtitulo">Características</h3>
<div class="stats-form">
    @foreach (['fuerza' => 'FUE', 'destreza' => 'DES', 'constitucion' => 'CON', 'inteligencia' => 'INT', 'sabiduria' => 'SAB', 'carisma' => 'CAR'] as $campo => $etiqueta)
        <div class="campo">
            <label for="{{ $p }}-{{ $campo }}">{{ $etiqueta }}</label>
            <input type="number" name="{{ $campo }}" id="{{ $p }}-{{ $campo }}" class="control" value="{{ $v($campo, 10) }}" min="1" max="30" inputmode="numeric" required>
        </div>
    @endforeach
</div>

<h3 class="form-subtitulo">Información adicional</h3>
<div class="campos-grid">
    <div class="campo campo--completo">
        <label for="{{ $p }}-descripcion">Descripción</label>
        <textarea name="descripcion" id="{{ $p }}-descripcion" class="control" rows="3">{{ $v('descripcion') }}</textarea>
    </div>
    <div class="campo">
        <label for="{{ $p }}-resistencias">Resistencias</label>
        <textarea name="resistencias" id="{{ $p }}-resistencias" class="control" rows="2" placeholder="Fuego, frío…">{{ $v('resistencias') }}</textarea>
    </div>
    <div class="campo">
        <label for="{{ $p }}-inmunidades">Inmunidades al daño</label>
        <textarea name="inmunidades_daño" id="{{ $p }}-inmunidades" class="control" rows="2">{{ $v('inmunidades_daño') }}</textarea>
    </div>
    <div class="campo">
        <label for="{{ $p }}-sentidos">Sentidos</label>
        <textarea name="sentidos" id="{{ $p }}-sentidos" class="control" rows="2" placeholder="Visión en la oscuridad 18 m…">{{ $v('sentidos') }}</textarea>
    </div>
    <div class="campo">
        <label for="{{ $p }}-idiomas">Idiomas</label>
        <input type="text" name="idiomas" id="{{ $p }}-idiomas" class="control" value="{{ $v('idiomas') }}" placeholder="Común, infernal…">
    </div>
    <div class="campo campo--completo">
        <label for="{{ $p }}-acciones">Acciones</label>
        <textarea name="acciones" id="{{ $p }}-acciones" class="control" rows="3" placeholder="Ataque con espada: +5 al ataque, 1d8+3 de daño cortante…">{{ $v('acciones') }}</textarea>
    </div>
    <div class="campo campo--completo">
        <label for="{{ $p }}-rasgos">Rasgos especiales</label>
        <textarea name="rasgos_especiales" id="{{ $p }}-rasgos" class="control" rows="3">{{ $v('rasgos_especiales') }}</textarea>
    </div>
</div>

<div class="campo">
    <span class="campo-etiqueta">Imagen</span>
    <img id="{{ $p }}-imagen-preview" class="en-preview" src="" alt="Vista previa de la imagen" data-lightbox hidden>
    <label class="subir-archivo" for="{{ $p }}-imagen">
        @include('partials.icon', ['name' => 'image'])
        <span data-archivo-nombre>Seleccionar imagen…</span>
    </label>
    <input type="file" name="imagen" id="{{ $p }}-imagen" class="sr-only" accept="image/jpeg,image/png,image/gif,image/webp" data-archivo-preview="{{ $p }}-imagen-preview">
</div>
