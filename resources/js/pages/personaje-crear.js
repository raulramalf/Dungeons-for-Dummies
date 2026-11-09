/* ============================================================================
   PÁGINA: personajes_crear — lógica extraída de la vista (antes un <script> en línea).
   Se carga como módulo (diferido): no bloquea el pintado y el navegador lo cachea.
   Las funciones usadas desde atributos onclick/onchange del HTML se exponen en window.
   ============================================================================ */

function filtrarSubclases() {
    const claseId = document.getElementById('claseSelect').value;
    const subclaseSelect = document.getElementById('subclaseSelect');
    let seleccionValida = false;

    [...subclaseSelect.options].forEach(opt => {
        if (!opt.value) { opt.style.display = ''; return; } // "Sin subclase todavía"
        const coincide = opt.dataset.clase === claseId;
        opt.style.display = coincide ? '' : 'none';
        if (coincide && opt.selected) seleccionValida = true;
    });

    if (!seleccionValida) subclaseSelect.value = '';
}
document.addEventListener('DOMContentLoaded', filtrarSubclases);

// Accesibles desde los atributos on*="…" del marcado
Object.assign(window, {
    filtrarSubclases,
});
