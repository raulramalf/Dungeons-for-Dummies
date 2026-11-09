/* ============================================================================
   PÁGINA: BESTIARIO
   · Búsqueda, ordenación (CR / nombre) y filtro "solo boss" sobre las tarjetas.
   · Abrir un enemigo: pide /enemigos/{id} (JSON) y rellena el modal de edición.
   · Vista previa de la imagen elegida.
   Todo con delegación de eventos y sin handlers inline.
   ============================================================================ */
import { abrirModal } from "../modal.js";
import { toast } from "../ui.js";

const $ = (id) => document.getElementById(id);

/* ----- Búsqueda, orden y filtro ----- */
const lista = $("lista-enemigos");

if (lista) {
    const estado = { texto: "", soloBoss: false, orden: null, asc: true };
    const sinResultados = $("en-sin-resultados");
    const norm = (t) => t.toLowerCase().normalize("NFD").replace(/\p{Diacritic}/gu, "");

    function aplicar() {
        const tarjetas = [...lista.querySelectorAll(".en-card")];
        const texto = norm(estado.texto.trim());
        let visibles = 0;

        for (const t of tarjetas) {
            const coincide = norm(t.dataset.nombre).includes(texto) && (!estado.soloBoss || t.dataset.boss === "1");
            t.hidden = !coincide;
            if (coincide) visibles++;
        }
        sinResultados.hidden = visibles > 0;

        if (estado.orden) {
            const dir = estado.asc ? 1 : -1;
            tarjetas
                .sort((a, b) =>
                    estado.orden === "cr"
                        ? (parseFloat(a.dataset.cr) - parseFloat(b.dataset.cr)) * dir
                        : a.dataset.nombre.localeCompare(b.dataset.nombre, "es") * dir,
                )
                .forEach((t) => lista.append(t));
        }

        document.querySelectorAll("[data-orden]").forEach((b) => {
            const activo = b.dataset.orden === estado.orden;
            b.setAttribute("aria-pressed", String(activo));
            b.querySelector("[data-flecha]").textContent = activo ? (estado.asc ? "↑" : "↓") : "↕";
        });
        document.querySelector("[data-solo-boss]")?.setAttribute("aria-pressed", String(estado.soloBoss));
    }

    $("buscador").addEventListener("input", (e) => {
        estado.texto = e.target.value;
        aplicar();
    });

    document.addEventListener("click", (e) => {
        const orden = e.target.closest("[data-orden]");
        if (orden) {
            // Mismo botón: alterna sentido. Otro botón: empieza ascendente.
            estado.asc = estado.orden === orden.dataset.orden ? !estado.asc : true;
            estado.orden = orden.dataset.orden;
            return aplicar();
        }
        if (e.target.closest("[data-solo-boss]")) {
            estado.soloBoss = !estado.soloBoss;
            aplicar();
        }
    });
}

/* ----- Abrir un enemigo (modal de edición) ----- */
// id del campo del formulario (sin el prefijo "ver-") → clave del JSON
const CAMPOS = {
    nombre: "nombre", tipo: "tipo", "tamaño": "tamaño", alineamiento: "alineamiento",
    cr: "clase_de_desafio", xp: "puntos_de_experiencia", ca: "clase_de_armadura",
    "tipo-armadura": "tipo_armadura", pg: "puntos_de_golpe", velocidad: "velocidad",
    fuerza: "fuerza", destreza: "destreza", constitucion: "constitucion",
    inteligencia: "inteligencia", sabiduria: "sabiduria", carisma: "carisma",
    descripcion: "descripcion", resistencias: "resistencias", inmunidades: "inmunidades_daño",
    sentidos: "sentidos", idiomas: "idiomas", acciones: "acciones", rasgos: "rasgos_especiales",
};

async function abrirEnemigo(id) {
    let e;
    try {
        const res = await fetch(`/enemigos/${encodeURIComponent(id)}`, { headers: { Accept: "application/json" } });
        if (!res.ok) throw new Error();
        e = await res.json();
    } catch {
        return toast("No se pudo cargar el enemigo. Inténtalo de nuevo.", "error");
    }

    $("ver-titulo").textContent = e.nombre;
    $("form-editar-enemigo").action = `/enemigos/${id}/update`;
    for (const [sufijo, clave] of Object.entries(CAMPOS)) {
        $(`ver-${sufijo}`).value = e[clave] ?? "";
    }
    $("ver-boss").checked = Number(e.es_boss) === 1;

    const preview = $("ver-imagen-preview");
    preview.hidden = !e.imagen;
    if (e.imagen) preview.src = `/storage/${e.imagen}`;
    document.querySelector("#modal-ver-enemigo [data-archivo-nombre]").textContent = "Seleccionar imagen…";
    $("ver-imagen").value = "";

    abrirModal("modal-ver-enemigo");
}

document.addEventListener("click", (e) => {
    const abrir = e.target.closest("[data-enemigo]");
    if (abrir) abrirEnemigo(abrir.dataset.enemigo);
});

/* ----- Vista previa de la imagen seleccionada ----- */
document.addEventListener("change", (e) => {
    const input = e.target.closest("input[data-archivo-preview]");
    if (!input) return;
    const archivo = input.files[0];
    input.closest(".campo").querySelector("[data-archivo-nombre]").textContent = archivo?.name || "Seleccionar imagen…";

    const preview = $(input.dataset.archivoPreview);
    if (archivo && preview) {
        preview.src = URL.createObjectURL(archivo);
        preview.hidden = false;
    }
});
