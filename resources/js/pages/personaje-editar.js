/* ============================================================================
   PÁGINA: personajes_editar — lógica extraída de la vista (antes un <script> en línea).
   Se carga como módulo (diferido): no bloquea el pintado y el navegador lo cachea.
   Las funciones usadas desde atributos onclick/onchange del HTML se exponen en window.
   ============================================================================ */
import { confirmar } from '../confirm.js';

/** Escapa texto para insertarlo dentro de HTML construido como cadena. */
const esc = (t) => String(t).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

/* ===== Eliminar imagen (personaje o arma) — formulario compartido fuera del form principal ===== */
async function eliminarImagen(tipo, index) {
    if (!(await confirmar('¿Eliminar imagen?'))) return;
    document.getElementById('eliminarImagenTipo').value = tipo;
    document.getElementById('eliminarImagenIndex').value = index;
    document.getElementById('formEliminarImagen').submit();
}

/* ===== Eliminar truco/conjuro — formulario compartido fuera del form principal =====
   (antes este botón vivía en un <form> anidado dentro del form de edición; el HTML
   no permite formularios anidados y el navegador colaba el _method=DELETE en el
   form principal, lo que acababa borrando el personaje entero en vez del truco) */
async function eliminarTruco(url, nombre) {
    if (!(await confirmar('¿Eliminar ' + nombre + '?'))) return;
    document.getElementById('formEliminarTruco').action = url;
    document.getElementById('formEliminarTruco').submit();
}

/* ===== Eliminar dote — formulario compartido fuera del form principal (mismo motivo que eliminarTruco) ===== */
async function eliminarDote(url, nombre) {
    if (!(await confirmar('¿Eliminar ' + nombre + '?'))) return;
    document.getElementById('formEliminarDote').action = url;
    document.getElementById('formEliminarDote').submit();
}

/* ===== Modificador automático al cambiar stats ===== */
function actualizarMod(input) {
    const val  = parseInt(input.value) || 10;
    const mod  = Math.floor((val - 10) / 2);
    const str  = mod >= 0 ? '+' + mod : String(mod);
    const span = document.getElementById('mod-' + input.name);
    if (span) span.textContent = str;
}

/* ===== Bonus de competencia automático según nivel (regla estándar 5e) ===== */
function actualizarBonusCompetencia() {
    const nivel = parseInt(document.getElementById('nivelInput').value) || 1;
    const bonus = 2 + Math.floor((Math.max(nivel, 1) - 1) / 4);
    document.getElementById('bonusCompetenciaDisplay').value = bonus;
}

/* ===== Preview de imágenes antes de subir ===== */
function previewImgs(input, containerId, max) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    const files = Array.from(input.files).slice(0, max);

    // Actualizar el input con solo los archivos permitidos
    const dt = new DataTransfer();
    files.forEach(f => dt.items.add(f));
    input.files = dt.files;

    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'img-existente';
            div.innerHTML = `<img src="${e.target.result}" alt="preview">`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

/* ===== Añadir fila de ataque ===== */
function addAtaque() {
    const row = document.createElement('div');
    row.className = 'ataque-row';
    row.innerHTML = `
        <input type="text" name="ataque_nombre[]" placeholder="Nombre del ataque">
        <input type="text" name="ataque_bonif[]" placeholder="+5">
        <input type="text" name="ataque_dano[]" placeholder="1d8+3 cortante">
        <button type="button" class="btn-del-ataque" onclick="this.closest('.ataque-row').remove()">✕</button>
    `;
    document.getElementById('ataquesList').appendChild(row);
}

/* ===== Añadir ataque precargado desde un objeto del equipo ===== */
function addAtaqueDesdeEquipo(select) {
    if (!select.value) return;
    const row = document.createElement('div');
    row.className = 'ataque-row';
    row.innerHTML = `
        <input type="text" name="ataque_nombre[]" placeholder="Nombre del ataque">
        <input type="text" name="ataque_bonif[]" placeholder="+5">
        <input type="text" name="ataque_dano[]" placeholder="1d8+3 cortante">
        <button type="button" class="btn-del-ataque" onclick="this.closest('.ataque-row').remove()">✕</button>
    `;
    row.querySelector('input[name="ataque_nombre[]"]').value = select.value;
    document.getElementById('ataquesList').appendChild(row);
    select.value = '';
}

/* ===== Serializar ataques antes de enviar ===== */
document.getElementById('formPersonaje').addEventListener('submit', function () {
    // Serializar ataques
    const nombres = [...document.querySelectorAll('input[name="ataque_nombre[]"]')].map(i => i.value);
    const bonifs  = [...document.querySelectorAll('input[name="ataque_bonif[]"]')].map(i => i.value);
    const danos   = [...document.querySelectorAll('input[name="ataque_dano[]"]')].map(i => i.value);

    const ataques = nombres.map((n, i) => ({
        nombre: n,
        bonificador: bonifs[i],
        daño: danos[i],
    })).filter(a => a.nombre);

    document.getElementById('ataquesHidden').value = JSON.stringify(ataques);

    // Serializar competencias habilidades
    const habChecked = [...document.querySelectorAll('input[name="competencias_habilidades[]"]:checked')].map(c => c.value);
    document.getElementById('hiddenHab').value = JSON.stringify(habChecked);
    document.querySelectorAll('input[name="competencias_habilidades[]"]').forEach(c => c.disabled = true);

    // Serializar competencias salvaciones
    const salChecked = [...document.querySelectorAll('input[name="competencias_salvaciones[]"]:checked')].map(c => c.value);
    document.getElementById('hiddenSal').value = JSON.stringify(salChecked);
    document.querySelectorAll('input[name="competencias_salvaciones[]"]').forEach(c => c.disabled = true);

    // Serializar conjuros nuevos seleccionados en el picker
    document.getElementById('conjurosNuevosHidden').value = JSON.stringify(conjurosSeleccionados);
    // Serializar dotes nuevas seleccionadas en el picker
    document.getElementById('dotesNuevosHidden').value = JSON.stringify(doteSeleccionados);
});

/* ===== Picker de conjuros: click para seleccionar/deseleccionar ===== */
let conjurosSeleccionados = [];

function toggleConjuro(row) {
    const id = parseInt(row.dataset.id);
    const nombre = row.dataset.nombreMostrar;
    const idx = conjurosSeleccionados.indexOf(id);

    if (idx === -1) {
        conjurosSeleccionados.push(id);
        row.classList.add('seleccionado');
    } else {
        conjurosSeleccionados.splice(idx, 1);
        row.classList.remove('seleccionado');
    }

    renderChips();
}

function quitarConjuroChip(id) {
    conjurosSeleccionados = conjurosSeleccionados.filter(x => x !== id);
    const row = document.querySelector(`#conjuroLista .conjuro-row[data-id="${id}"]`);
    if (row) row.classList.remove('seleccionado');
    renderChips();
}

function renderChips() {
    const cont = document.getElementById('conjuroChips');
    if (conjurosSeleccionados.length === 0) {
        cont.innerHTML = '<span class="conjuro-chips-vacio">Ningún conjuro seleccionado todavía — haz click en uno de la lista</span>';
        return;
    }
    cont.innerHTML = conjurosSeleccionados.map(id => {
        const row = document.querySelector(`#conjuroLista .conjuro-row[data-id="${id}"]`);
        const nombre = row ? row.dataset.nombreMostrar : id;
        return `<span class="conjuro-chip">${esc(nombre)}<button type="button" onclick="quitarConjuroChip(${id})" aria-label="Quitar ${esc(nombre)}">✕</button></span>`;
    }).join('');
}

/* ===== Picker de conjuros: filtro de búsqueda ===== */
function filtrarConjuros(texto) {
    const t = texto.trim().toLowerCase();
    document.querySelectorAll('#conjuroLista .conjuro-row').forEach(row => {
        row.style.display = row.dataset.nombre.includes(t) ? '' : 'none';
    });
    document.querySelectorAll('#conjuroLista .conjuro-grupo').forEach(grupo => {
        const visibles = [...grupo.querySelectorAll('.conjuro-row')].some(r => r.style.display !== 'none');
        grupo.style.display = visibles ? '' : 'none';
    });
}

/* ===== Picker de dotes: click para seleccionar/deseleccionar ===== */
let doteSeleccionados = [];

function toggleDote(row) {
    const id = parseInt(row.dataset.id);
    const idx = doteSeleccionados.indexOf(id);

    if (idx === -1) {
        doteSeleccionados.push(id);
        row.classList.add('seleccionado');
    } else {
        doteSeleccionados.splice(idx, 1);
        row.classList.remove('seleccionado');
    }

    renderDoteChips();
}

function quitarDoteChip(id) {
    doteSeleccionados = doteSeleccionados.filter(x => x !== id);
    const row = document.querySelector(`#doteLista .conjuro-row[data-id="${id}"]`);
    if (row) row.classList.remove('seleccionado');
    renderDoteChips();
}

function renderDoteChips() {
    const cont = document.getElementById('doteChips');
    if (doteSeleccionados.length === 0) {
        cont.innerHTML = '<span class="conjuro-chips-vacio">Ninguna dote seleccionada todavía — haz click en una de la lista</span>';
        return;
    }
    cont.innerHTML = doteSeleccionados.map(id => {
        const row = document.querySelector(`#doteLista .conjuro-row[data-id="${id}"]`);
        const nombre = row ? row.dataset.nombreMostrar : id;
        return `<span class="conjuro-chip">${nombre}<button type="button" onclick="quitarDoteChip(${id})">✕</button></span>`;
    }).join('');
}

/* ===== Picker de dotes: filtro de búsqueda ===== */
function filtrarDotes(texto) {
    const t = texto.trim().toLowerCase();
    document.querySelectorAll('#doteLista .conjuro-row').forEach(row => {
        row.style.display = row.dataset.nombre.includes(t) ? '' : 'none';
    });
    document.querySelectorAll('#doteLista .conjuro-grupo').forEach(grupo => {
        const visibles = [...grupo.querySelectorAll('.conjuro-row')].some(r => r.style.display !== 'none');
        grupo.style.display = visibles ? '' : 'none';
    });
}

/* ===== Filtrar subclases según la clase elegida ===== */
function filtrarSubclases() {
    const claseId = document.getElementById('claseSelect').value;
    const subclaseSelect = document.getElementById('subclaseSelect');
    let seleccionValida = false;

    [...subclaseSelect.options].forEach(opt => {
        if (!opt.value) { opt.style.display = ''; return; }
        const coincide = opt.dataset.clase === claseId;
        opt.style.display = coincide ? '' : 'none';
        if (coincide && opt.selected) seleccionValida = true;
    });

    if (!seleccionValida) subclaseSelect.value = '';
}
document.addEventListener('DOMContentLoaded', filtrarSubclases);

// Accesibles desde los atributos on*="…" del marcado
Object.assign(window, {
    actualizarBonusCompetencia,
    actualizarMod,
    addAtaque,
    addAtaqueDesdeEquipo,
    eliminarDote,
    eliminarImagen,
    eliminarTruco,
    filtrarConjuros,
    filtrarDotes,
    filtrarSubclases,
    previewImgs,
    quitarConjuroChip,
    quitarDoteChip,
    toggleConjuro,
    toggleDote,
});
