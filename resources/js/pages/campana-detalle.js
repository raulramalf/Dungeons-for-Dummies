/* ============================================================================
   PÁGINA: DETALLE DE CAMPAÑA
   Modales de edición y ficha resumida de un personaje.

   SEGURIDAD: los datos de la ficha (rasgos, vínculos, equipo, alineamiento…) los
   escribe OTRO usuario. Antes se interpolaban con innerHTML sin escapar, lo que
   permitía ejecutar código en el navegador del DM al abrir su ficha. Ahora todo
   valor dinámico pasa por esc() o por textContent.
   ============================================================================ */
import { abrirModal } from "../modal.js";
import { toast } from "../ui.js";

const campanaId = document.getElementById("campana-datos")?.dataset.campanaId;

/** Escapa texto para insertarlo dentro de HTML construido como cadena. */
const esc = (t) =>
    String(t ?? "").replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" })[c]);

const $ = (id) => document.getElementById(id);
const signo = (n) => (n >= 0 ? "+" : "") + n;

/* ----- Editar sesión / nota: los datos viajan en data-* del botón (Blade los escapa) ----- */
function editarSesion(d) {
    $("form-editar-sesion").action = `/campanyas/${campanaId}/sesiones/${d.id}`;
    $("edit-titulo").value = d.titulo;
    $("edit-numero").value = d.numero;
    $("edit-fecha").value = d.fecha || "";
    $("edit-resumen").value = d.resumen || "";
    $("edit-duracion").value = d.duracion || "";
    abrirModal("modal-editar-sesion");
}

function editarNota(d) {
    $("form-editar-nota").action = `/campanyas/${campanaId}/notas/${d.id}`;
    $("edit-nota-titulo").value = d.titulo;
    $("edit-nota-contenido").value = d.contenido;
    $("edit-nota-visible").checked = d.visible === "true";
    abrirModal("modal-editar-nota");
}

/* ----- Ficha resumida de un personaje ----- */
async function verPersonaje(id) {
    abrirModal("modal-personaje");
    $("modal-p-nombre").textContent = "Cargando…";

    let p;
    try {
        const res = await fetch(`/personajes/${id}/json?campana_id=${encodeURIComponent(campanaId)}`, {
            headers: { Accept: "application/json" },
        });
        if (!res.ok) throw new Error();
        p = await res.json();
    } catch {
        $("modal-p-nombre").textContent = "No se pudo cargar la ficha";
        return toast("No se pudo cargar la ficha del personaje.", "error");
    }

    const avatar = $("modal-p-avatar");
    avatar.src = p.avatar_url;
    avatar.alt = p.nombre;

    $("modal-p-nombre").textContent = p.nombre;
    $("modal-p-subtitulo").textContent = [p.raza, p.clase, p.subclase].filter(Boolean).join(" · ");

    $("modal-p-badges").innerHTML =
        `<span class="pjm-badge pjm-badge--nivel">Nivel ${esc(p.nivel)}</span>` +
        (p.alineamiento ? `<span class="pjm-badge">${esc(p.alineamiento)}</span>` : "") +
        (p.trasfondo ? `<span class="pjm-badge">${esc(p.trasfondo)}</span>` : "");

    const etiquetas = ["FUE", "DES", "CON", "INT", "SAB", "CAR"];
    const valores = [p.fuerza, p.destreza, p.constitucion, p.inteligencia, p.sabiduria, p.carisma];
    $("modal-p-stats").innerHTML = etiquetas
        .map((e, i) => {
            const mod = Math.floor((valores[i] - 10) / 2);
            return `<div class="pjm-stat"><span class="pjm-stat-et">${e}</span><span class="pjm-stat-val">${esc(valores[i])}</span><span class="pjm-stat-mod">${signo(mod)}</span></div>`;
        })
        .join("");

    const combate = [
        ["PG", `${p.pg_actuales ?? "?"}/${p.pg_maximos ?? "?"}`],
        ["CA", p.clase_de_armadura ?? "—"],
        ["Velocidad", `${p.velocidad ?? 30} ft`],
        ["Competencia", `+${p.bonus_competencia ?? 2}`],
        ["Iniciativa", p.iniciativa !== null && p.iniciativa !== undefined ? signo(p.iniciativa) : "—"],
    ];
    $("modal-p-combate").innerHTML = combate
        .map(([et, val]) => `<div class="pjm-dato"><span class="pjm-dato-et">${esc(et)}</span><span class="pjm-dato-val">${esc(val)}</span></div>`)
        .join("");

    $("modal-p-historia-sec").hidden = !p.historia;
    if (p.historia) $("modal-p-historia").textContent = p.historia;

    const rasgos = [
        ["Rasgos", p.rasgos_personalidad],
        ["Ideales", p.ideales],
        ["Vínculos", p.vinculos],
        ["Defectos", p.defectos],
    ].filter(([, v]) => v);
    $("modal-p-personalidad-sec").hidden = rasgos.length === 0;
    $("modal-p-personalidad").innerHTML = rasgos
        .map(([et, v]) => `<div class="pjm-rasgo"><span class="pjm-rasgo-et">${esc(et)}</span><span class="pjm-rasgo-txt">${esc(v)}</span></div>`)
        .join("");

    const equipo = p.equipo ?? [];
    $("modal-p-equipo-sec").hidden = equipo.length === 0;
    $("modal-p-equipo").innerHTML = equipo
        .map(
            (e) =>
                `<div class="pjm-equipo"><span class="pjm-equipo-nombre">${esc(e.nombre)}${e.equipado ? ' <span class="pjm-equipado">✓</span>' : ""}</span>` +
                `<div class="pjm-equipo-tipo">${esc(e.tipo)}${e.cantidad > 1 ? ` ×${esc(e.cantidad)}` : ""}</div></div>`,
        )
        .join("");
}

/* ----- Un único listener de clic ----- */
document.addEventListener("click", (e) => {
    const sesion = e.target.closest("[data-editar-sesion]");
    if (sesion) return editarSesion(sesion.dataset);

    const nota = e.target.closest("[data-editar-nota]");
    if (nota) return editarNota(nota.dataset);

    const pj = e.target.closest("[data-ver-personaje]");
    if (pj) verPersonaje(pj.dataset.verPersonaje);
});
