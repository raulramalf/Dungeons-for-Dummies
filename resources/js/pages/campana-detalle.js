/* ============================================================================
   PÁGINA: DETALLE DE CAMPAÑA
   Modales de edición y ficha resumida de un personaje.

   SEGURIDAD: los datos de la ficha (rasgos, vínculos, equipo, alineamiento…) los
   escribe OTRO usuario. Antes se interpolaban con innerHTML sin escapar, lo que
   permitía ejecutar código en el navegador del DM al abrir su ficha. Ahora todo
   valor dinámico pasa por esc() o por textContent.
   ============================================================================ */
import { abrirLightbox } from "../lightbox.js";

const campanaId = document.getElementById("campana-datos")?.dataset.campanaId;

/** Escapa texto para insertarlo dentro de HTML construido como cadena. */
const esc = (t) =>
    String(t ?? "").replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" })[c]);

const $ = (id) => document.getElementById(id);

function abrirModal(id) {
    const modal = $(id);
    modal.scrollTop = 0;
    modal.style.display = "flex";
}

function abrirModalEditar() {
    abrirModal("modal-editar-campana");
}

function abrirModalSesion() {
    abrirModal("modal-sesion");
}

function abrirModalNota() {
    abrirModal("modal-nota");
}

function editarSesion(id, titulo, numero, fecha, resumen, duracion) {
    $("form-editar-sesion").action = `/campanyas/${campanaId}/sesiones/${id}`;
    $("edit-titulo").value = titulo;
    $("edit-numero").value = numero;
    $("edit-fecha").value = fecha ? fecha.substring(0, 10) : "";
    $("edit-resumen").value = resumen;
    $("edit-duracion").value = duracion || "";
    abrirModal("modal-editar-sesion");
}

function editarNotaDesdeCard(el) {
    $("form-editar-nota").action = `/campanyas/${campanaId}/notas/${el.dataset.notaId}`;
    $("edit-nota-titulo").value = el.dataset.notaTitulo;
    $("edit-nota-contenido").value = el.dataset.notaContenido;
    $("edit-nota-visible").checked = el.dataset.notaVisible === "true";
    abrirModal("modal-editar-nota");
}

const signo = (n) => (n >= 0 ? "+" : "") + n;

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
        return;
    }

    const avatar = $("modal-p-avatar");
    avatar.src = p.avatar_url;
    avatar.style.cursor = "zoom-in";
    avatar.onclick = (e) => {
        e.stopPropagation();
        abrirLightbox(p.avatar_url, p.nombre);
    };

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

    $("modal-p-historia-sec").style.display = p.historia ? "block" : "none";
    if (p.historia) $("modal-p-historia").textContent = p.historia;

    const rasgos = [
        ["Rasgos", p.rasgos_personalidad],
        ["Ideales", p.ideales],
        ["Vínculos", p.vinculos],
        ["Defectos", p.defectos],
    ].filter(([, v]) => v);
    $("modal-p-personalidad-sec").style.display = rasgos.length ? "block" : "none";
    $("modal-p-personalidad").innerHTML = rasgos
        .map(([et, v]) => `<div class="pjm-rasgo"><span class="pjm-rasgo-et">${esc(et)}</span><span class="pjm-rasgo-txt">${esc(v)}</span></div>`)
        .join("");

    const equipo = p.equipo ?? [];
    $("modal-p-equipo-sec").style.display = equipo.length ? "block" : "none";
    $("modal-p-equipo").innerHTML = equipo
        .map(
            (e) =>
                `<div class="pjm-equipo"><span class="pjm-equipo-nombre">${esc(e.nombre)}${e.equipado ? ' <span class="pjm-equipado">✓</span>' : ""}</span>` +
                `<div class="pjm-equipo-tipo">${esc(e.tipo)}${e.cantidad > 1 ? ` ×${esc(e.cantidad)}` : ""}</div></div>`,
        )
        .join("");
}

// Accesibles desde los atributos on*="…" del marcado
Object.assign(window, {
    abrirModalEditar,
    abrirModalNota,
    abrirModalSesion,
    editarNotaDesdeCard,
    editarSesion,
    verPersonaje,
});
