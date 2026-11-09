/* ============================================================================
   PÁGINA: campanyas — lógica extraída de la vista (antes un <script> en línea).
   Se carga como módulo (diferido): no bloquea el pintado y el navegador lo cachea.
   Las funciones usadas desde atributos onclick/onchange del HTML se exponen en window.
   ============================================================================ */

function cambiarTabCampana(tab) {
    document.querySelectorAll('.panel-campana').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.tab-campana').forEach(t => {
        t.style.borderBottomColor = 'transparent';
        t.style.color = 'var(--t-secundario)';
    });

    document.getElementById('panel-' + tab).style.display = 'flex';
    const tabBtn = document.getElementById('tab-' + tab);
    tabBtn.style.borderBottomColor = 'var(--c-rojo)';
    tabBtn.style.color = 'white';
}

function abrirModalCrear() {
    document.getElementById('modal-crear-campana').scrollTop = 0;
    document.getElementById('modal-crear-campana').style.display = 'flex';
}

// Accesibles desde los atributos on*="…" del marcado
Object.assign(window, {
    abrirModalCrear,
    cambiarTabCampana,
});
