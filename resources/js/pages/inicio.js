/* ============================================================================
   PÁGINA: INICIO — calendario de sesiones y dado d20 (carga diferida)
   ============================================================================ */
import { iniciarCalendario } from "../calendario.js";

const calendario = document.querySelector("[data-calendario]");
if (calendario) iniciarCalendario(calendario);

/* --- Dado 3D: three.js se descarga de forma diferida (nunca bloquea el primer
       pintado): en escritorio cuando el navegador está libre; en móvil, al primer toque. --- */
const dado = document.getElementById("d20-container");

if (dado) {
    let dadoListo = null; // Promise<{lanzar}|null>
    let tiradaPendiente = false;

    const cargarDado = () =>
        (dadoListo ??= import("../d20.js").then((m) => m.iniciarD20(dado)));

    // Un toque antes de que cargue three.js no se pierde: tira en cuanto esté listo
    dado.addEventListener(
        "click",
        () => {
            if (dado.dataset.d20Init) return; // ya montado: lo gestiona d20.js
            tiradaPendiente = true;
            dado.classList.add("cargando");
            cargarDado().then((d) => {
                dado.classList.remove("cargando");
                if (tiradaPendiente && d) d.lanzar();
                tiradaPendiente = false;
            });
        },
        { capture: true },
    );

    // En móvil o con ahorro de datos NO se precarga (≈130 KB comprimidos): se
    // descarga solo si la persona toca el dado. En escritorio, cuando haya un hueco.
    const ahorrarDatos =
        navigator.connection?.saveData || window.matchMedia("(max-width: 1024px)").matches;

    if (!ahorrarDatos) {
        if ("requestIdleCallback" in window) requestIdleCallback(cargarDado, { timeout: 3000 });
        else setTimeout(cargarDado, 1500);
    }
}
