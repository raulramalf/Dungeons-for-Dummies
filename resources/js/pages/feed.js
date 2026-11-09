/* ============================================================================
   PÁGINA: LA TABERNA
   Interacciones del feed con delegación de eventos (sin onclick inline) y DOM
   construido con textContent: el texto de usuario nunca se interpreta como HTML.
   ============================================================================ */
import { toast } from "../ui.js";
import { confirmar } from "../confirm.js";

const raiz = document.querySelector("[data-feed]");

if (raiz) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? "";
    const { urlLike, urlComentarios, urlFeed } = raiz.dataset;

    const api = (url, { method = "POST", json } = {}) =>
        fetch(url, {
            method,
            headers: {
                "X-CSRF-TOKEN": csrf,
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                ...(json ? { "Content-Type": "application/json" } : {}),
            },
            body: json ? JSON.stringify(json) : undefined,
        });

    /* ----- Etiquetas del formulario de publicación ----- */
    const tags = [];
    const tagInput = document.getElementById("tagInput");
    const tagsDisplay = document.getElementById("tagsDisplay");
    const tagsHidden = document.getElementById("etiquetasHidden");

    function pintarTags() {
        if (!tagsDisplay) return;
        tagsDisplay.replaceChildren(
            ...tags.map((t, i) => {
                const chip = document.createElement("button");
                chip.type = "button";
                chip.className = "tag";
                chip.dataset.tagQuitar = i;
                chip.setAttribute("aria-label", `Quitar etiqueta ${t}`);
                chip.textContent = `#${t} ✕`;
                return chip;
            }),
        );
        tagsHidden.value = JSON.stringify(tags);
    }

    function añadirTag() {
        const val = tagInput.value.trim().toLowerCase().replace(/\s+/g, "-").slice(0, 30);
        if (val && !tags.includes(val) && tags.length < 8) {
            tags.push(val);
            pintarTags();
            tagInput.value = "";
        }
        tagInput.focus();
    }

    tagInput?.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            añadirTag();
        }
    });

    /* ----- Me gusta ----- */
    async function alternarLike(btn) {
        btn.disabled = true;
        try {
            const res = await api(urlLike, { json: { id: btn.dataset.id, type: btn.dataset.type } });
            if (!res.ok) throw new Error();
            const data = await res.json();
            if (data.success) {
                btn.querySelector(".like-count").textContent = data.count;
                btn.classList.toggle("liked", data.liked);
                btn.setAttribute("aria-pressed", String(data.liked));
            }
        } catch {
            toast("No se pudo registrar el me gusta. Inténtalo de nuevo.", "error");
        } finally {
            btn.disabled = false;
        }
    }

    /* ----- Borrado (publicación o comentario) ----- */
    async function borrar({ url, mensaje, elemento, boton }) {
        if (!(await confirmar(mensaje))) return;
        boton.disabled = true;
        try {
            const res = await api(url, { method: "DELETE" });
            if (!res.ok) throw new Error();
            if ((await res.json()).success) {
                elemento.style.transition = "opacity 0.3s";
                elemento.style.opacity = "0";
                setTimeout(() => elemento.remove(), 300);
            }
        } catch {
            boton.disabled = false;
            toast("No se pudo eliminar. Inténtalo de nuevo.", "error");
        }
    }

    function alternarPanel(panel, boton) {
        panel.hidden = !panel.hidden;
        boton?.setAttribute("aria-expanded", String(!panel.hidden));
        if (!panel.hidden) panel.querySelector("textarea")?.focus();
    }

    /* ----- Un único listener de clic para todo el feed ----- */
    raiz.addEventListener("click", (e) => {
        const t = e.target.closest("button");
        if (!t) return;

        if (t.hasAttribute("data-tag-add")) return añadirTag();

        if (t.dataset.tagQuitar !== undefined) {
            tags.splice(Number(t.dataset.tagQuitar), 1);
            return pintarTags();
        }

        if (t.hasAttribute("data-like")) return alternarLike(t);

        if (t.dataset.comentarios) {
            return alternarPanel(document.getElementById(`comments-${t.dataset.comentarios}`), t);
        }

        if (t.dataset.responder) {
            return alternarPanel(document.getElementById(t.dataset.responder));
        }

        if (t.dataset.borrarPost) {
            const id = t.dataset.borrarPost;
            return borrar({
                url: `${urlFeed}/${id}`,
                mensaje: "¿Seguro que quieres borrar esta publicación? También se borrarán sus comentarios.",
                elemento: document.getElementById(`post-${id}`),
                boton: t,
            });
        }

        if (t.dataset.borrarComentario) {
            const id = t.dataset.borrarComentario;
            return borrar({
                url: `${urlComentarios}/${id}`,
                mensaje: "¿Seguro que quieres borrar este comentario?",
                elemento: document.getElementById(`comment-${id}`),
                boton: t,
            });
        }
    });

    /* ----- Textareas que crecen con el contenido ----- */
    raiz.addEventListener("input", (e) => {
        const ta = e.target.closest("textarea[data-autosize]");
        if (!ta) return;
        ta.style.height = "auto";
        ta.style.height = `${ta.scrollHeight}px`;
    });

    /* ----- Salas: la activa queda visible en la tira desplazable (móvil) ----- */
    raiz.querySelector('.sala-tab[aria-current="page"]')?.scrollIntoView({
        inline: "center",
        block: "nearest",
    });

    /* ----- "Cargar más": botón + carga automática al acercarse al final ----- */
    const lista = raiz.querySelector("[data-feed-lista]");
    let cargando = false;

    const observador =
        "IntersectionObserver" in window
            ? new IntersectionObserver(
                  (entradas) => entradas.some((en) => en.isIntersecting) && cargarMas(),
                  { rootMargin: "400px 0px" },
              )
            : null;

    function vigilar() {
        const disparador = lista.querySelector("[data-feed-mas]");
        if (disparador) observador?.observe(disparador);
    }

    async function cargarMas() {
        const disparador = lista.querySelector("[data-feed-mas]");
        if (!disparador || cargando) return;
        cargando = true;
        observador?.unobserve(disparador);
        const boton = disparador.querySelector("button");
        boton.disabled = true;
        boton.textContent = "Cargando…";

        try {
            const url = new URL(disparador.dataset.siguiente);
            url.searchParams.set("parcial", "1");
            const res = await fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } });
            if (!res.ok) throw new Error();
            const plantilla = document.createElement("template");
            plantilla.innerHTML = await res.text();
            disparador.replaceWith(plantilla.content);
            vigilar();
        } catch {
            boton.disabled = false;
            boton.textContent = "Reintentar";
            toast("No se pudieron cargar más publicaciones.", "error");
        } finally {
            cargando = false;
        }
    }

    lista.addEventListener("click", (e) => {
        if (e.target.closest("[data-feed-mas] button")) cargarMas();
    });
    vigilar();
}
