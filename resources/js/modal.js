/* ============================================================================
   MODALES (<dialog class="modal">)
   · Abrir:  <button data-modal-abrir="id-del-dialog">
   · Cerrar: <button data-modal-cerrar>  (o Esc, o clic en el fondo)
   · Desde JS: abrirModal('id')
   Sustituye a los <div style="display:none; position:fixed…"> que se abrían con
   style.display='flex': sin foco atrapado, sin Esc y con scroll del fondo.
   ============================================================================ */
export function abrirModal(id) {
    const dialogo = document.getElementById(id);
    if (!dialogo || dialogo.open) return dialogo;
    dialogo.showModal();
    dialogo.querySelector(".modal-cuerpo")?.scrollTo(0, 0);
    return dialogo;
}

export function cerrarModal(id) {
    document.getElementById(id)?.close();
}

const fuera = (dialogo, e) => {
    const r = dialogo.getBoundingClientRect();
    return e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom;
};

// El fondo solo cierra si el gesto EMPEZÓ y terminó fuera (evita cerrar al
// soltar el ratón tras seleccionar texto dentro de un campo)
let empezoFuera = false;
document.addEventListener("pointerdown", (e) => {
    const d = e.target;
    empezoFuera = d instanceof HTMLDialogElement && d.classList.contains("modal") && fuera(d, e);
});

document.addEventListener("click", (e) => {
    const abrir = e.target.closest("[data-modal-abrir]");
    if (abrir) return void abrirModal(abrir.dataset.modalAbrir);

    const cerrar = e.target.closest("[data-modal-cerrar]");
    if (cerrar) return void cerrar.closest("dialog")?.close();

    const d = e.target;
    if (empezoFuera && d instanceof HTMLDialogElement && d.classList.contains("modal") && fuera(d, e)) {
        d.close();
    }
    empezoFuera = false;
});

window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;

// Un modal marcado con data-abrir-al-cargar se muestra al cargar la página
// (p. ej. para reabrir un formulario cuando el servidor devuelve errores de validación).
document.querySelectorAll("dialog.modal[data-abrir-al-cargar]").forEach((d) => d.showModal());
