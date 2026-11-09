/* ============================================================================
   VÍDEO DE FONDO CONDICIONAL
   Solo se descarga en escritorio, sin "reducir movimiento" y sin ahorro de
   datos ni conexión lenta. El póster WebP ya está pintado por CSS, así que
   en el resto de casos no se pide el MP4 (≈2,7 MB) en absoluto.
   ============================================================================ */
const contenedor = document.querySelector("[data-bg-video]");

if (contenedor) {
    const escritorio = window.matchMedia("(min-width: 1025px)");
    const sinMovimiento = window.matchMedia("(prefers-reduced-motion: reduce)");
    let video = null;

    const conexionLenta = () => {
        const c = navigator.connection;
        return Boolean(c && (c.saveData || /(^|-)2g$/.test(c.effectiveType || "")));
    };

    const permitido = () =>
        escritorio.matches && !sinMovimiento.matches && !conexionLenta();

    const cargar = () => {
        if (video || !permitido()) return;
        video = document.createElement("video");
        video.muted = true;
        video.loop = true;
        video.playsInline = true;
        video.autoplay = true;
        video.preload = "auto";
        video.setAttribute("aria-hidden", "true");
        video.src = contenedor.dataset.bgVideo;
        video.addEventListener("canplay", () => video.classList.add("listo"), {
            once: true,
        });
        contenedor.prepend(video);
        video.play().catch(() => {});
    };

    const cuandoLibre = () =>
        "requestIdleCallback" in window
            ? requestIdleCallback(cargar, { timeout: 2500 })
            : setTimeout(cargar, 1000);

    cuandoLibre();
    // Si la ventana pasa a ser de escritorio tras cargar la página
    escritorio.addEventListener("change", cuandoLibre);

    // No gastar CPU/GPU con la pestaña oculta
    document.addEventListener("visibilitychange", () => {
        if (!video) return;
        document.hidden ? video.pause() : video.play().catch(() => {});
    });
}
