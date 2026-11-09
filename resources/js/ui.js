/* ============================================================================
   UI COMPARTIDA: hoja "Más" (móvil) y toasts
   ============================================================================ */

/* ----- Toasts ----- */
const TOAST_MS = 5000;

function quitarToast(toast) {
    if (!toast || toast.classList.contains("saliendo")) return;
    toast.classList.add("saliendo");
    setTimeout(() => toast.remove(), 350);
}

function programarToast(toast) {
    let timer;
    const arrancar = () => (timer = setTimeout(() => quitarToast(toast), TOAST_MS));
    const parar = () => clearTimeout(timer);
    // No desaparece mientras se lee o se interactúa con él
    toast.addEventListener("pointerenter", parar);
    toast.addEventListener("focusin", parar);
    toast.addEventListener("pointerleave", arrancar);
    toast.addEventListener("focusout", arrancar);
    arrancar();
}

const ICONO_ERROR =
    '<svg class="icon toast-icono" viewBox="0 0 24 24"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>';
const ICONO_OK =
    '<svg class="icon toast-icono" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>';

/** Muestra un toast desde JS. El texto se inserta con textContent (sin XSS). */
export function toast(mensaje, tipo = "exito") {
    const contenedor = document.getElementById("toasts");
    if (!contenedor) return;

    const el = document.createElement("div");
    el.className = `toast toast-${tipo === "error" ? "error" : "exito"}`;
    el.setAttribute("role", tipo === "error" ? "alert" : "status");
    el.insertAdjacentHTML("afterbegin", tipo === "error" ? ICONO_ERROR : ICONO_OK);

    const texto = document.createElement("span");
    texto.className = "toast-texto";
    texto.textContent = mensaje;
    el.append(texto);

    const cerrar = document.createElement("button");
    cerrar.type = "button";
    cerrar.className = "toast-cerrar";
    cerrar.setAttribute("aria-label", "Cerrar");
    cerrar.dataset.toastCerrar = "";
    cerrar.innerHTML =
        '<svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>';
    el.append(cerrar);

    contenedor.append(el);
    programarToast(el);
}
window.toast = toast;

document
    .querySelectorAll("#toasts .toast")
    .forEach((t) => programarToast(t));

/* ----- Clics delegados: abrir/cerrar hoja, cerrar toast ----- */
document.addEventListener("click", (e) => {
    const abrir = e.target.closest("[data-sheet]");
    if (abrir) {
        document.getElementById(abrir.dataset.sheet)?.showModal();
        return;
    }

    const hoja = e.target.closest("dialog.sheet");
    if (hoja) {
        // Clic en el fondo (el propio <dialog>) o en un enlace de la hoja
        if (e.target === hoja || e.target.closest("a[href]")) hoja.close();
        return;
    }

    const cerrarToast = e.target.closest("[data-toast-cerrar]");
    if (cerrarToast) quitarToast(cerrarToast.closest(".toast"));
});

/* Al volver con "atrás" (bfcache) la hoja no debe quedar abierta */
window.addEventListener("pageshow", () => {
    document.querySelectorAll("dialog.sheet[open]").forEach((d) => d.close());
});

/* Si se ensancha la ventana hasta modo escritorio, cierra la hoja */
window.matchMedia("(min-width: 1025px)").addEventListener("change", (m) => {
    if (m.matches)
        document.querySelectorAll("dialog.sheet[open]").forEach((d) => d.close());
});

/* ----- Auto-envío: <select|input data-autosubmit> envía su formulario al cambiar ----- */
document.addEventListener("change", (e) => {
    const campo = e.target.closest?.("[data-autosubmit]");
    if (campo?.form) campo.form.submit();
});
