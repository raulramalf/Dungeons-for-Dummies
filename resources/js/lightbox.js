/* ============================================================================
   LIGHTBOX GLOBAL
   Cualquier <img data-lightbox> se amplía en un <dialog> modal (foco atrapado,
   Esc para cerrar). Sustituye a los lightbox copiados en cada vista.
   ============================================================================ */
let dialogo;

function crear() {
    const d = document.createElement("dialog");
    d.className = "lightbox";
    d.setAttribute("aria-label", "Imagen ampliada");
    d.innerHTML = '<img alt="">';
    // Un clic en cualquier punto cierra (incluido el fondo)
    d.addEventListener("click", () => d.close());
    document.body.append(d);
    return d;
}

export function abrirLightbox(src, alt = "") {
    dialogo ??= crear();
    const img = dialogo.querySelector("img");
    img.src = src;
    img.alt = alt;
    dialogo.showModal();
}

document.addEventListener("click", (e) => {
    const img = e.target.closest("img[data-lightbox]");
    if (!img) return;
    e.preventDefault();
    e.stopPropagation();
    abrirLightbox(img.currentSrc || img.src, img.alt);
});
