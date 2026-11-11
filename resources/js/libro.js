/* ============================================================================
   LIBRO — pasa la hoja al navegar entre secciones.
   Intercepta los enlaces internos, anima la página actual (giro sobre el lomo)
   y entonces navega. Sin JS o con «reducir movimiento» se navega sin efecto.
   ============================================================================ */
const DURACION = 560;
const pagina = () => document.querySelector(".libro-pagina");
const reducir = () => matchMedia("(prefers-reduced-motion: reduce)").matches;

function esEnlaceInterno(a, e) {
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return false;
    if (a.target && a.target !== "_self") return false;
    if (a.hasAttribute("download") || a.hasAttribute("data-sin-hoja") || a.hasAttribute("data-lightbox")) return false;
    const url = new URL(a.href, location.href);
    if (url.origin !== location.origin) return false;
    if (url.pathname === location.pathname && url.search === location.search) return false; // ancla o misma página
    if (/\.(pdf|png|jpe?g|webp|svg|zip)$/i.test(url.pathname) || url.pathname.includes("/exportar/descargar")) return false;
    return true;
}

let pasando = false;

document.addEventListener("click", (e) => {
    const a = e.target.closest?.("a[href]");
    const hoja = pagina();
    if (!a || !hoja || pasando || reducir() || !esEnlaceInterno(a, e)) return;

    e.preventDefault();
    pasando = true;

    // El giro se centra en lo que el usuario está viendo, aunque haya hecho scroll
    const caja = hoja.getBoundingClientRect();
    const y = Math.min(Math.max(innerHeight / 2 - caja.top, 0), caja.height);
    hoja.style.transformOrigin = `left ${y}px`;
    hoja.classList.add("pasando");

    setTimeout(() => { location.href = a.href; }, DURACION);
});

// Al volver con «atrás» (caché del navegador) la hoja debe estar en su sitio
addEventListener("pageshow", (e) => {
    const hoja = pagina();
    if (!hoja) return;
    pasando = false;
    hoja.classList.remove("pasando");
    hoja.style.transformOrigin = "";
    if (!e.persisted && !reducir()) {
        hoja.classList.add("entrando");
        setTimeout(() => hoja.classList.remove("entrando"), 700);
    }
});
