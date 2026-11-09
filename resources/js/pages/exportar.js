/* ============================================================================
   PÁGINA: EXPORTAR FICHA — actualiza el enlace de descarga según la selección.
   La URL base llega en data-base (antes se interpolaba dentro de un <script>).
   ============================================================================ */
const enlace = document.getElementById("enlaceExportar");

if (enlace) {
    const actualizar = () => {
        const tipo = document.querySelector('input[name="tipoFicha"]:checked')?.value;
        const plantilla = document.querySelector('input[name="plantillaFicha"]:checked')?.value;
        if (!tipo || !plantilla) return;
        const url = new URL(enlace.dataset.base, window.location.origin);
        url.searchParams.set("plantilla", plantilla);
        url.searchParams.set("tipo", tipo);
        enlace.href = url.pathname + url.search;
    };

    document.addEventListener("change", (e) => {
        if (e.target.closest("[data-actualiza-enlace]")) actualizar();
    });
    actualizar();
}
