/* ============================================================================
   CALENDARIO DE SESIONES (Inicio)
   Los datos llegan en `data-sesiones` (JSON escapado por Blade). Todo el texto
   de usuario (campaña, título) se inserta con textContent: nunca como HTML.
   ============================================================================ */
const MESES = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre",
];

const pad = (n) => String(n).padStart(2, "0");
/** YYYY-MM-DD en hora LOCAL (toISOString usa UTC y marcaba mal el "hoy"). */
const clave = (y, m, d) => `${y}-${pad(m + 1)}-${pad(d)}`;

function el(tag, clase, texto) {
    const e = document.createElement(tag);
    if (clase) e.className = clase;
    if (texto != null) e.textContent = texto;
    return e;
}

export function iniciarCalendario(raiz) {
    let sesiones = {};
    try {
        sesiones = JSON.parse(raiz.dataset.sesiones || "{}");
    } catch {
        sesiones = {};
    }

    const titulo = raiz.querySelector("[data-cal-titulo]");
    const grid = raiz.querySelector("[data-cal-grid]");
    // El panel de detalle es hermano del calendario, dentro de la misma sección
    const detalle = (raiz.closest("section") ?? document).querySelector("[data-cal-detalle]");
    let fecha = new Date();
    fecha.setDate(1);
    let seleccionado = null;

    function mostrarDia(k) {
        seleccionado = k;
        detalle.replaceChildren();
        const lista = sesiones[k] || [];
        if (!lista.length) return;

        const f = new Date(`${k}T00:00:00`);
        detalle.append(
            el(
                "h3",
                "cal-detalle-titulo",
                f.toLocaleDateString("es-ES", { weekday: "long", day: "numeric", month: "long" }),
            ),
        );
        for (const s of lista) {
            const fila = el("a", "cal-sesion-item");
            fila.href = `/campanyas/${encodeURIComponent(s.campana_id)}`;
            fila.append(
                el("strong", null, s.campana),
                el("span", "cal-sesion-txt", `Sesión ${s.numero}: ${s.titulo}`),
                el("span", "cal-sesion-hora", s.hora || ""),
            );
            detalle.append(fila);
        }
        grid.querySelectorAll(".cal-celda").forEach((c) =>
            c.classList.toggle("cal-activa", c.dataset.fecha === k),
        );
    }

    function render() {
        const year = fecha.getFullYear();
        const month = fecha.getMonth();
        titulo.textContent = `${MESES[month]} ${year}`;
        grid.replaceChildren();

        // Lunes = 0
        const offset = (new Date(year, month, 1).getDay() + 6) % 7;
        const dias = new Date(year, month + 1, 0).getDate();
        const hoy = new Date();
        const hoyK = clave(hoy.getFullYear(), hoy.getMonth(), hoy.getDate());

        for (let i = 0; i < offset; i++) grid.append(el("div", "cal-celda cal-vacia"));

        for (let dia = 1; dia <= dias; dia++) {
            const k = clave(year, month, dia);
            const delDia = sesiones[k];
            const esHoy = k === hoyK;
            let celda;

            if (delDia) {
                celda = el("button", "cal-celda cal-con-sesion");
                celda.type = "button";
                celda.setAttribute(
                    "aria-label",
                    `${dia} de ${MESES[month]}: ${delDia.length} sesión(es). ${delDia.map((s) => s.campana).join(", ")}`,
                );
                celda.addEventListener("click", () => mostrarDia(k));
            } else {
                celda = el("div", "cal-celda");
            }
            celda.dataset.fecha = k;
            if (esHoy) {
                celda.classList.add("cal-hoy");
                celda.setAttribute("aria-current", "date");
            }
            celda.append(el("span", "cal-numero", dia));

            if (delDia) {
                celda.append(el("span", "cal-campana-label", delDia[0].campana));
                celda.append(el("span", "cal-punto"));
                if (delDia.length > 1) celda.append(el("span", "cal-mas", `+${delDia.length - 1}`));
            }
            grid.append(celda);
        }

        // Al cambiar de mes se limpia el detalle salvo que el día elegido siga visible
        if (!seleccionado || !seleccionado.startsWith(`${year}-${pad(month + 1)}`)) {
            detalle.replaceChildren();
            seleccionado = null;
        } else {
            mostrarDia(seleccionado);
        }
    }

    function cambiarMes(delta) {
        fecha = new Date(fecha.getFullYear(), fecha.getMonth() + delta, 1);
        render();
    }

    raiz.querySelector("[data-cal-prev]").addEventListener("click", () => cambiarMes(-1));
    raiz.querySelector("[data-cal-next]").addEventListener("click", () => cambiarMes(1));
    raiz.querySelector("[data-cal-hoy]").addEventListener("click", () => {
        fecha = new Date();
        fecha.setDate(1);
        render();
    });

    render();
}
